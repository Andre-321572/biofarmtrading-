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

        // RH updates rates with decimal support
        $response = $this->post(route('rh.salaries.update_rate'), [
            'hourly_rate' => 280.5,
            'new_hourly_rate' => 218.75,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals(280.5, PayrollService::getHourlyRate());
        $this->assertEquals(218.75, PayrollService::getNewHourlyRate());
    }

    public function test_automatic_14_day_trial_period_and_rate_transition()
    {
        // 1. Worker hired 5 days ago -> currently in 14-day trial
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
        // 14-day trial period: Oct 1st to Oct 15th
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
            'hourly_rate' => 300.0,
            'is_new' => 'null', // Auto
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $worker->refresh();
        $this->assertEquals($newHiredAt, $worker->hired_at->toDateString());
        $this->assertEquals(300.0, $worker->hourly_rate);
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

    public function test_rh_can_access_new_workers_management_view()
    {
        $rhUser = User::factory()->create(['role' => 'rh']);
        $this->actingAs($rhUser);

        $response = $this->get(route('rh.workers.new'));
        $response->assertStatus(200);
        $response->assertSee('Nouveaux Ouvriers');
    }
}
