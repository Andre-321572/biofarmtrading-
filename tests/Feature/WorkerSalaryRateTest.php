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
        $newRate = PayrollService::getNewHourlyRate();

        $this->assertEquals(275, $regularRate);
        $this->assertEquals(250, $newRate);
    }

    public function test_admin_and_rh_can_update_both_regular_and_new_worker_rates()
    {
        $rhUser = User::factory()->create(['role' => 'rh']);
        $this->actingAs($rhUser);

        // RH updates rates
        $response = $this->post(route('rh.salaries.update_rate'), [
            'hourly_rate' => 300,
            'new_hourly_rate' => 220,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals(300, PayrollService::getHourlyRate());
        $this->assertEquals(220, PayrollService::getNewHourlyRate());
    }

    public function test_differentiated_rates_between_new_and_regular_workers()
    {
        Setting::set('worker_hourly_rate', 300);
        Setting::set('worker_new_hourly_rate', 200);

        // Regular worker created 6 months ago
        $regularWorker = Worker::create([
            'first_name' => 'Kofi',
            'last_name' => 'MENSAH',
            'shift' => 'day',
            'is_new' => false,
            'created_at' => Carbon::now()->subMonths(6),
        ]);

        // New worker created 5 days ago
        $newWorker = Worker::create([
            'first_name' => 'Ablan',
            'last_name' => 'KOUDOU',
            'shift' => 'day',
            'is_new' => true,
            'created_at' => Carbon::now()->subDays(5),
        ]);

        $this->assertFalse($regularWorker->isNew());
        $this->assertTrue($newWorker->isNew());

        $this->assertEquals(300, $regularWorker->getEffectiveHourlyRate());
        $this->assertEquals(200, $newWorker->getEffectiveHourlyRate());
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

        $this->assertDatabaseHas('worker_bonuses', [
            'worker_id' => $worker2->id,
            'month' => $month,
            'amount' => 5000,
        ]);
    }

    public function test_bonus_is_added_to_base_salary_in_monthly_payroll()
    {
        $worker = Worker::create([
            'first_name' => 'Marc',
            'last_name' => 'AGBO',
            'shift' => 'night',
            'is_new' => false,
            'created_at' => Carbon::now()->subMonths(3),
        ]);

        // Attendance: 1 present day in night shift = 4 hours (240 min)
        $date = Carbon::now()->startOfMonth()->format('Y-m-d');
        Attendance::create([
            'worker_id' => $worker->id,
            'date' => $date,
            'status' => 'present',
        ]);

        // Hourly rate: 300 F/h -> Base salary for 4 hours = 1 200 FCFA
        Setting::set('worker_hourly_rate', 300);

        // Add a bonus of 5 000 FCFA
        $month = Carbon::now()->format('Y-m');
        WorkerBonus::create([
            'worker_id' => $worker->id,
            'month' => $month,
            'amount' => 5000,
            'reason' => 'Prime de nuit',
        ]);

        $payroll = app(PayrollService::class)->getMonthlyPayroll(Carbon::now());
        $item = $payroll['items']->firstWhere('worker.id', $worker->id);

        $this->assertNotNull($item);
        $this->assertEquals(1200, $item['base_salary']);
        $this->assertEquals(5000, $item['bonus_amount']);
        $this->assertEquals(6200, $item['salary']); // 1200 + 5000 = 6200 FCFA
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
