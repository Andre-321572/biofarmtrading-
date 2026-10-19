<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Worker;
use App\Models\Attendance;
use App\Models\Setting;
use App\Models\WorkerBonus;
use App\Services\PayrollService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkerSalaryRateTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_worker_rates()
    {
        $regularRate = PayrollService::getHourlyRate();
        $trialRate = PayrollService::getNewHourlyRate();

        $this->assertEquals(275.0, $regularRate);
        $this->assertEquals(218.75, $trialRate);
    }

    public function test_admin_and_rh_can_update_both_regular_and_trial_worker_rates_with_decimals()
    {
        $rhUser = User::factory()->create(['role' => 'rh']);
        $this->actingAs($rhUser);

        // RH updates rates with French comma decimal support
        $response = $this->post(route('rh.salaries.update_rate'), [
            'hourly_rate' => '280,50',
            'new_hourly_rate' => '218,75',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals(280.50, PayrollService::getHourlyRate());
        $this->assertEquals(218.75, PayrollService::getNewHourlyRate());
    }

    public function test_automatic_15_day_trial_period_and_rate_transition()
    {
        // 1. Worker hired 5 days ago -> currently in 15-day trial
        $trialWorker = Worker::create([
            'first_name' => 'Ablan',
            'last_name' => 'KOUDOU',
            'shift' => 'day',
            'hired_at' => Carbon::now()->subDays(5)->toDateString(),
        ]);

        // 2. Worker hired 25 days ago -> trial ended, automatically confirmed
        $confirmedWorker = Worker::create([
            'first_name' => 'Kofi',
            'last_name' => 'MENSAH',
            'shift' => 'day',
            'hired_at' => Carbon::now()->subDays(25)->toDateString(),
        ]);

        $this->assertTrue($trialWorker->isCurrentlyInTrial());
        $this->assertTrue($trialWorker->isNew());
        $this->assertEquals(218.75, $trialWorker->getEffectiveHourlyRate());

        $this->assertFalse($confirmedWorker->isCurrentlyInTrial());
        $this->assertFalse($confirmedWorker->isNew());
        $this->assertEquals(275.0, $confirmedWorker->getEffectiveHourlyRate());
    }

    public function test_mid_month_split_trial_and_regular_salary_calculation()
    {
        // Month of October 2026
        $month = Carbon::create(2026, 10, 1);
        Setting::set('worker_hourly_rate', 275.0, 'float');
        Setting::set('worker_new_hourly_rate', 218.75, 'float');

        // Worker hired on October 1st, 2026
        // 15-day trial period: Oct 1st to Oct 15th
        // From Oct 16th onward: Regular rate (275 FCFA)
        $worker = Worker::create([
            'first_name' => 'Jean',
            'last_name' => 'KODJO',
            'shift' => 'night', // 4 hours per day (240 min)
            'hired_at' => '2026-10-01',
        ]);

        // 10 days of work during trial period (Oct 1 to Oct 10) = 10 days * 4h = 40 hours
        for ($day = 1; $day <= 10; $day++) {
            Attendance::create([
                'worker_id' => $worker->id,
                'date' => sprintf('2026-10-%02d', $day),
                'status' => 'present',
            ]);
        }

        // 5 days of work after trial period (Oct 20 to Oct 24) = 5 days * 4h = 20 hours
        for ($day = 20; $day <= 24; $day++) {
            Attendance::create([
                'worker_id' => $worker->id,
                'date' => sprintf('2026-10-%02d', $day),
                'status' => 'present',
            ]);
        }

        $payrollService = app(PayrollService::class);
        $payroll = $payrollService->getMonthlyPayroll($month);
        $item = $payroll['items']->firstWhere('worker.id', $worker->id);

        $this->assertNotNull($item);
        // 40 hours * 60 = 2400 trial minutes
        $this->assertEquals(2400, $item['trial_minutes']);
        // 20 hours * 60 = 1200 regular minutes
        $this->assertEquals(1200, $item['regular_minutes']);
        $this->assertTrue($item['is_split_rate']);

        // Expected trial salary: 40 hours * 218.75 = 8 750 FCFA
        // Expected regular salary: 20 hours * 275.0 = 5 500 FCFA
        // Total Base Salary = 8 750 + 5 500 = 14 250 FCFA
        $this->assertEquals(14250, $item['base_salary']);
        $this->assertEquals(14250, $item['salary']);
    }

    public function test_rh_and_admin_can_update_worker_hired_at_and_status()
    {
        $rhUser = User::factory()->create(['role' => 'rh']);
        $this->actingAs($rhUser);

        $worker = Worker::create([
            'first_name' => 'Paul',
            'last_name' => 'AGBO',
            'shift' => 'day',
        ]);

        $newHiredAt = Carbon::now()->subDays(3)->toDateString();

        $response = $this->patch(route('rh.workers.update_status', $worker->id), [
            'hired_at' => $newHiredAt,
            'hourly_rate' => '218,75',
            'is_new' => 'null', // Auto
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $worker->refresh();
        $this->assertEquals($newHiredAt, $worker->hired_at->toDateString());
        $this->assertEquals(218.75, $worker->hourly_rate);
    }

    public function test_rh_and_admin_can_assign_bonuses_to_selected_workers()
    {
        $rhUser = User::factory()->create(['role' => 'rh']);
        $this->actingAs($rhUser);

        $worker1 = Worker::create(['first_name' => 'Jean', 'last_name' => 'DUPONT', 'shift' => 'day']);
        $worker2 = Worker::create(['first_name' => 'Paul', 'last_name' => 'DURAND', 'shift' => 'night']);

        $month = Carbon::now()->format('Y-m');

        // Assign bonus of 5000 FCFA to both workers
        $response = $this->post(route('rh.salaries.bonuses.store'), [
            'worker_ids' => [$worker1->id, $worker2->id],
            'month' => $month,
            'amount' => 5000,
            'reason' => 'Prime de rendement exceptionnel',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('worker_bonuses', [
            'worker_id' => $worker1->id,
            'month' => $month,
            'amount' => 5000,
        ]);
    }

    public function test_pdf_export_hides_hourly_rate_column_for_confidentiality()
    {
        $rhUser = User::factory()->create(['role' => 'rh']);
        $this->actingAs($rhUser);

        $response = $this->get(route('rh.salaries.pdf'));
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    public function test_rh_and_admin_can_cancel_bonus_with_mandatory_reason()
    {
        $rhUser = User::factory()->create(['role' => 'rh']);
        $this->actingAs($rhUser);

        $worker = Worker::create(['first_name' => 'Koffi', 'last_name' => 'MENSAH', 'shift' => 'day']);
        $month = Carbon::now()->format('Y-m');

        $bonus = WorkerBonus::create([
            'worker_id' => $worker->id,
            'month' => $month,
            'amount' => 5000,
            'reason' => 'Prime de présence',
            'created_by' => $rhUser->id,
        ]);

        $response = $this->delete(route('rh.salaries.bonuses.destroy', $bonus), [
            'cancellation_reason' => 'Erreur de saisie lors de la sélection des ouvriers',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Vérifier que la prime est bien soft-deleted avec la justification et l'auteur
        $this->assertSoftDeleted('worker_bonuses', [
            'id' => $bonus->id,
            'cancellation_reason' => 'Erreur de saisie lors de la sélection des ouvriers',
            'cancelled_by' => $rhUser->id,
        ]);

        // Vérifier que la paie mensuelle recalculée exclut cette prime annulée
        $payrollService = app(\App\Services\PayrollService::class);
        $payroll = $payrollService->getMonthlyPayroll(Carbon::now());
        $workerItem = collect($payroll['items'])->firstWhere('worker.id', $worker->id);
        $this->assertEquals(0, $workerItem['bonus_amount']);
    }

    public function test_cancellation_reason_is_strictly_required_to_cancel_bonus()
    {
        $rhUser = User::factory()->create(['role' => 'rh']);
        $this->actingAs($rhUser);

        $worker = Worker::create(['first_name' => 'Ablam', 'last_name' => 'KOUDOU', 'shift' => 'day']);
        $month = Carbon::now()->format('Y-m');

        $bonus = WorkerBonus::create([
            'worker_id' => $worker->id,
            'month' => $month,
            'amount' => 10000,
            'reason' => 'Prime exceptionnelle',
            'created_by' => $rhUser->id,
        ]);

        // Tentative sans motif
        $response = $this->delete(route('rh.salaries.bonuses.destroy', $bonus), [
            'cancellation_reason' => '',
        ]);

        $response->assertSessionHasErrors(['cancellation_reason']);

        // La prime ne doit PAS être supprimée
        $this->assertDatabaseHas('worker_bonuses', [
            'id' => $bonus->id,
            'deleted_at' => null,
        ]);
    }

    public function test_new_workers_page_strictly_excludes_workers_past_15_days_trial()
    {
        $rhUser = User::factory()->create(['role' => 'rh']);
        $this->actingAs($rhUser);

        // Ouvrier 1 : embauché il y a 3 jours (en cours des 15 jours d'essai)
        $trialWorker = Worker::create([
            'first_name' => 'Paul',
            'last_name' => 'EN_ESSAI',
            'shift' => 'day',
            'hired_at' => Carbon::now()->subDays(3)->toDateString(),
        ]);

        // Ouvrier 2 : embauché il y a 20 jours (essai 15j terminé -> ancien)
        $ancienWorker = Worker::create([
            'first_name' => 'Jean',
            'last_name' => 'ANCIEN_TITULARISE',
            'shift' => 'day',
            'hired_at' => Carbon::now()->subDays(20)->toDateString(),
        ]);

        $response = $this->get(route('rh.workers.new'));
        $response->assertStatus(200);

        // L'ouvrier en cours d'essai DOIT être affiché
        $response->assertSee('EN_ESSAI');

        // L'ouvrier ayant fini ses 15 jours d'essai NE DOIT PLUS être affiché
        $response->assertDontSee('ANCIEN_TITULARISE');
    }

    public function test_rh_and_admin_can_export_emargement_pdf_without_salaries()
    {
        $rhUser = User::factory()->create(['role' => 'rh']);
        $this->actingAs($rhUser);

        Worker::create([
            'first_name' => 'Paul',
            'last_name' => 'KOUDOU',
            'shift' => 'day',
            'hired_at' => Carbon::now()->subDays(10)->toDateString(),
        ]);

        $response = $this->get(route('rh.salaries.emargement'));
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));

        // Vérifier également pour l'admin
        $adminUser = User::factory()->create(['role' => 'admin']);
        $this->actingAs($adminUser);

        $adminResponse = $this->get(route('admin.salaries.emargement'));
        $adminResponse->assertStatus(200);
        $this->assertEquals('application/pdf', $adminResponse->headers->get('content-type'));
    }

    public function test_titularisation_does_not_retroactively_alter_trial_period_rates()
    {
        // Mois d'octobre 2026
        $month = Carbon::create(2026, 10, 1);
        Setting::set('worker_hourly_rate', 275.0, 'float');
        Setting::set('worker_new_hourly_rate', 218.75, 'float');

        // Ouvrier embauché le 1er octobre 2026
        // Période d'essai : 1er au 15 octobre inclus
        // Titularisé comme Ancien après sa période d'essai (ou par la RH)
        $worker = Worker::create([
            'first_name' => 'Komi',
            'last_name' => 'TITULARISE_APRES_ESSAI',
            'shift' => 'night', // 4h/j
            'hired_at' => '2026-10-01',
            'tenured_at' => '2026-10-16',
            'is_new' => false, // Titularisé comme Ancien
        ]);

        // 10 jours de travail pendant la période d'essai (1er au 10 oct) = 40h
        for ($day = 1; $day <= 10; $day++) {
            Attendance::create([
                'worker_id' => $worker->id,
                'date' => sprintf('2026-10-%02d', $day),
                'status' => 'present',
            ]);
        }

        // 5 jours de travail après la période d'essai (20 au 24 oct) = 20h
        for ($day = 20; $day <= 24; $day++) {
            Attendance::create([
                'worker_id' => $worker->id,
                'date' => sprintf('2026-10-%02d', $day),
                'status' => 'present',
            ]);
        }

        $payrollService = app(PayrollService::class);
        $payroll = $payrollService->getMonthlyPayroll($month);
        $item = $payroll['items']->firstWhere('worker.id', $worker->id);

        $this->assertNotNull($item);
        // Les 40 heures d'essai doivent STRICTEMENT rester au taux d'essai 218.75 FCFA
        $this->assertEquals(2400, $item['trial_minutes']); // 40h
        $this->assertEquals(1200, $item['regular_minutes']); // 20h
        $this->assertTrue($item['is_split_rate']);

        // Salaire période d'essai = 40 * 218.75 = 8 750 FCFA
        // Salaire titularisé = 20 * 275.0 = 5 500 FCFA
        // Salaire total de base = 14 250 FCFA (et NON 60 * 275 = 16 500 FCFA)
        $this->assertEquals(14250, $item['base_salary']);
        $this->assertEquals(14250, $item['salary']);
    }

    public function test_rh_can_revert_mistaken_titularisation_back_to_trial()
    {
        $rhUser = User::factory()->create(['role' => 'rh']);
        $this->actingAs($rhUser);

        // Ouvrier embauché il y a 5 jours
        $worker = Worker::create([
            'first_name' => 'Ablan',
            'last_name' => 'ERREUR_TITULARISATION',
            'shift' => 'day',
            'hired_at' => Carbon::now()->subDays(5)->toDateString(),
            'is_new' => null,
        ]);

        $this->assertTrue($worker->isNew());

        // 1. La RH titularise par erreur cet ouvrier
        $response1 = $this->patch(route('rh.workers.update_status', $worker->id), [
            'action' => 'titulariser',
        ]);
        $response1->assertRedirect();
        $response1->assertSessionHas('success');

        $worker->refresh();
        $this->assertFalse($worker->isNew());
        $this->assertNotNull($worker->tenured_at);

        // 2. La RH s'aperçoit de l'erreur et clique pour remédier (remettre en période d'essai)
        $response2 = $this->patch(route('rh.workers.update_status', $worker->id), [
            'action' => 'revert_trial',
        ]);
        $response2->assertRedirect();
        $response2->assertSessionHas('success');

        $worker->refresh();
        // L'ouvrier doit immédiatement redevenir en période d'essai
        $this->assertTrue($worker->isNew());
        $this->assertTrue($worker->isCurrentlyInTrial());
        $this->assertNull($worker->tenured_at);
    }

    public function test_tenured_tab_on_new_workers_page_displays_tenured_workers()
    {
        $rhUser = User::factory()->create(['role' => 'rh']);
        $this->actingAs($rhUser);

        // Ouvrier titularisé
        $ancien = Worker::create([
            'first_name' => 'Koffi',
            'last_name' => 'WORKER_CONFIRME',
            'shift' => 'day',
            'hired_at' => Carbon::now()->subDays(30)->toDateString(),
            'is_new' => false,
        ]);

        // Ouvrier en essai
        $essai = Worker::create([
            'first_name' => 'Abla',
            'last_name' => 'WORKER_EN_ESSAI',
            'shift' => 'day',
            'hired_at' => Carbon::now()->subDays(5)->toDateString(),
        ]);

        // Sur l'onglet 'trial', on ne voit que l'ouvrier en essai
        $responseTrial = $this->get(route('rh.workers.new', ['tab' => 'trial']));
        $responseTrial->assertSee('WORKER_EN_ESSAI');
        $responseTrial->assertDontSee('WORKER_CONFIRME');

        // Sur l'onglet 'tenured', on voit l'ouvrier confirmé
        $responseTenured = $this->get(route('rh.workers.new', ['tab' => 'tenured']));
        $responseTenured->assertSee('WORKER_CONFIRME');
    }
}



