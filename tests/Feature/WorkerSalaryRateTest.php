<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Worker;
use App\Models\Attendance;
use App\Models\Setting;
use App\Services\PayrollService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkerSalaryRateTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_worker_hourly_rate_is_275()
    {
        $rate = PayrollService::getHourlyRate();
        $this->assertEquals(275, $rate);
    }

    public function test_admin_can_update_worker_hourly_rate_and_it_applies_automatically()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        // Update rate to 350 FCFA
        $response = $this->post(route('admin.salaries.update_rate'), [
            'hourly_rate' => 350,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check setting in DB and Service
        $this->assertEquals(350, Setting::get('worker_hourly_rate'));
        $this->assertEquals(350, PayrollService::getHourlyRate());

        // Check payroll service calculations use new rate
        $payroll = app(PayrollService::class)->getMonthlyPayroll(Carbon::now());
        $this->assertEquals(350, $payroll['hourly_rate']);
    }

    public function test_non_admin_cannot_update_worker_hourly_rate()
    {
        $rhUser = User::factory()->create([
            'role' => 'rh',
        ]);

        $this->actingAs($rhUser);

        $response = $this->post(route('admin.salaries.update_rate'), [
            'hourly_rate' => 400,
        ]);

        $response->assertRedirect('/');
        $this->assertNotEquals(400, PayrollService::getHourlyRate());
        $this->assertEquals(275, PayrollService::getHourlyRate());
    }

    public function test_hourly_rate_validation_rules()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        // Negative or zero rate
        $response = $this->post(route('admin.salaries.update_rate'), [
            'hourly_rate' => 0,
        ]);
        $response->assertSessionHasErrors('hourly_rate');

        // Non numeric
        $response = $this->post(route('admin.salaries.update_rate'), [
            'hourly_rate' => 'abc',
        ]);
        $response->assertSessionHasErrors('hourly_rate');
    }

    public function test_salary_index_and_pdf_generation_use_configured_rate()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        // Update rate to 320
        Setting::set('worker_hourly_rate', 320);

        // Check index view
        $indexResponse = $this->get(route('admin.salaries.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('320 FCFA');

        // Check PDF generation
        $pdfResponse = $this->get(route('admin.salaries.pdf'));
        $pdfResponse->assertStatus(200);
        $this->assertEquals('application/pdf', $pdfResponse->headers->get('content-type'));
    }

    public function test_admin_dashboard_displays_hourly_rate_configuration_widget()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        Setting::set('worker_hourly_rate', 275);

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Taux Horaire des Ouvriers');
        $response->assertSee('275');
        $response->assertSee(route('admin.salaries.update_rate'));
    }
}
