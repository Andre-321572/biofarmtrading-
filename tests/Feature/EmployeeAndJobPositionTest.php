<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\JobPosition;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeAndJobPositionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_job_positions_index()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        JobPosition::create([
            'title' => 'Comptable',
            'department' => 'Finance & Comptabilité',
            'description' => 'Gestion de la trésorerie et de la comptabilité générale',
            'is_active' => true,
        ]);

        $response = $this->get(route('admin.job_positions.index'));
        $response->assertStatus(200);
        $response->assertSee('Comptable');
        $response->assertSee('Finance & Comptabilité');
    }

    public function test_rh_can_create_and_update_job_position()
    {
        $rh = User::factory()->create(['role' => 'rh']);
        $this->actingAs($rh);

        // Création
        $response = $this->post(route('rh.job_positions.store'), [
            'title' => 'Chauffeur Livreur',
            'department' => 'Logistique',
            'description' => 'Conduite des véhicules de distribution',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('rh.job_positions.index'));
        $this->assertDatabaseHas('job_positions', [
            'title' => 'Chauffeur Livreur',
            'department' => 'Logistique',
            'is_active' => true,
        ]);

        $position = JobPosition::where('title', 'Chauffeur Livreur')->first();

        // Mise à jour
        $updateResponse = $this->put(route('rh.job_positions.update', $position->id), [
            'title' => 'Chauffeur Poids Lourd',
            'department' => 'Transport & Logistique',
            'description' => 'Transport de gros tonnage',
            'is_active' => '1',
        ]);

        $updateResponse->assertRedirect(route('rh.job_positions.index'));
        $this->assertDatabaseHas('job_positions', [
            'id' => $position->id,
            'title' => 'Chauffeur Poids Lourd',
            'department' => 'Transport & Logistique',
        ]);
    }

    public function test_admin_can_create_employee_with_auto_matricule_and_comma_salary()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $position = JobPosition::create([
            'title' => 'Gardien de Nuit',
            'department' => 'Sécurité',
            'is_active' => true,
        ]);

        $response = $this->post(route('admin.employees.store'), [
            'first_name' => 'Koffi',
            'last_name' => 'Yao',
            'gender' => 'M',
            'job_position_id' => $position->id,
            'contract_type' => 'CDI',
            'hired_at' => '2026-01-15',
            'monthly_salary' => '175000,50', // test support virgule
            'status' => 'actif',
            'phone' => '+225 07 11 22 33 44',
            'notes' => 'Posté au portail principal',
        ]);

        $response->assertRedirect(route('admin.employees.index'));
        $this->assertDatabaseHas('employees', [
            'first_name' => 'Koffi',
            'last_name' => 'Yao',
            'job_position_id' => $position->id,
            'monthly_salary' => 175000.50,
            'status' => 'actif',
        ]);

        $employee = Employee::where('first_name', 'Koffi')->first();
        $this->assertNotNull($employee->matricule);
        $this->assertStringStartsWith('EMP-', $employee->matricule);
        $this->assertEquals('Koffi Yao', $employee->full_name);
        $this->assertEquals('Gardien de Nuit', $employee->jobPosition->title);
    }

    public function test_rh_can_edit_and_update_employee()
    {
        $rh = User::factory()->create(['role' => 'rh']);
        $this->actingAs($rh);

        $position1 = JobPosition::create(['title' => 'Électricien']);
        $position2 = JobPosition::create(['title' => 'Chef Électricien']);

        $employee = Employee::create([
            'first_name' => 'Amadou',
            'last_name' => 'Diallo',
            'job_position_id' => $position1->id,
            'contract_type' => 'CDD',
            'status' => 'actif',
            'monthly_salary' => 200000,
        ]);

        $response = $this->put(route('rh.employees.update', $employee->id), [
            'first_name' => 'Amadou',
            'last_name' => 'Diallo',
            'job_position_id' => $position2->id,
            'contract_type' => 'CDI',
            'status' => 'actif',
            'monthly_salary' => '250000',
        ]);

        $response->assertRedirect(route('rh.employees.index'));
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'job_position_id' => $position2->id,
            'contract_type' => 'CDI',
            'monthly_salary' => 250000,
        ]);
    }

    public function test_cannot_delete_job_position_if_employees_are_assigned()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $position = JobPosition::create(['title' => 'Mécanicien']);
        Employee::create([
            'first_name' => 'Sékou',
            'last_name' => 'Touré',
            'job_position_id' => $position->id,
            'status' => 'actif',
        ]);

        $response = $this->delete(route('admin.job_positions.destroy', $position->id));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('job_positions', ['id' => $position->id]);
    }

    public function test_unauthorized_user_cannot_access_postes_or_employees()
    {
        $client = User::factory()->create(['role' => 'customer']);
        $this->actingAs($client);

        $response1 = $this->get(route('admin.job_positions.index'));
        $response1->assertRedirect();

        $response2 = $this->get(route('rh.employees.index'));
        $response2->assertRedirect();
    }

    public function test_admin_can_update_employee_job_position()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $employee = Employee::create([
            'first_name' => 'Paul',
            'last_name' => 'Kone',
            'contract_type' => 'CDI',
            'status' => 'actif',
        ]);

        $newPosition = JobPosition::create([
            'title' => 'Chef d\'équipe',
            'department' => 'Production & Usine',
        ]);

        $response = $this->patch(route('admin.employees.update_position', $employee->id), [
            'job_position_id' => $newPosition->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'job_position_id' => $newPosition->id,
        ]);
    }

    public function test_admin_can_export_personnel_pdf_without_workers()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Création d'un membre du personnel permanent
        Employee::create([
            'matricule' => 'BFT-DIR-TEST',
            'first_name' => 'Directeur',
            'last_name' => 'Test',
            'contract_type' => 'CDI',
            'monthly_salary' => 500000,
            'status' => 'actif',
        ]);

        // Création d'un ouvrier (qui ne doit pas être inclus)
        \App\Models\Worker::create([
            'first_name' => 'Ouvrier',
            'last_name' => 'Fabrique',
            'shift' => 'day',
        ]);

        $response = $this->get(route('admin.employees.pdf'));
        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }
}

