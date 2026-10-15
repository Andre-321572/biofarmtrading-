<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\JobPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Détermine le préfixe de route en fonction du rôle de l'utilisateur connecté.
     */
    protected function getRoutePrefix(): string
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return 'admin.';
        }
        if (Auth::check() && Auth::user()->isRh()) {
            return 'rh.';
        }
        return 'admin.';
    }

    /**
     * Liste du personnel de Bio Farm Trading.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $positionId = $request->input('position_id');
        $status = $request->input('status');
        $contractType = $request->input('contract_type');

        $query = Employee::with('jobPosition')
            ->orderBy('last_name', 'asc')
            ->orderBy('first_name', 'asc');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($positionId)) {
            $query->where('job_position_id', $positionId);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($contractType)) {
            $query->where('contract_type', $contractType);
        }

        $employees = $query->paginate(20)->withQueryString();

        // Statistiques globales
        $totalStaff = Employee::count();
        $activeStaff = Employee::where('status', 'actif')->count();
        $onLeaveStaff = Employee::where('status', 'conge')->count();
        $totalMonthlyPayroll = Employee::where('status', 'actif')->sum('monthly_salary') ?? 0;

        // Postes disponibles pour les filtres et attributions
        $positions = JobPosition::active()->orderBy('title', 'asc')->get();

        $prefix = $this->getRoutePrefix();

        return view('admin.employees.index', compact(
            'employees',
            'positions',
            'totalStaff',
            'activeStaff',
            'onLeaveStaff',
            'totalMonthlyPayroll',
            'search',
            'positionId',
            'status',
            'contractType',
            'prefix'
        ));
    }

    /**
     * Formulaire de création d'un employé.
     */
    public function create()
    {
        $positions = JobPosition::active()->orderBy('title', 'asc')->get();
        $prefix = $this->getRoutePrefix();

        return view('admin.employees.create', compact('positions', 'prefix'));
    }

    /**
     * Enregistre un nouvel employé dans la base de données.
     */
    public function store(Request $request)
    {
        // Normaliser le salaire si saisi avec une virgule française
        if ($request->has('monthly_salary') && is_string($request->input('monthly_salary'))) {
            $request->merge([
                'monthly_salary' => str_replace(',', '.', trim($request->input('monthly_salary'))),
            ]);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'job_position_id' => 'nullable|exists:job_positions,id',
            'matricule' => 'nullable|string|max:50|unique:employees,matricule',
            'gender' => 'nullable|in:M,F',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string|max:255',
            'national_id' => 'nullable|string|max:100',
            'contract_type' => 'nullable|string|max:50',
            'hired_at' => 'nullable|date',
            'monthly_salary' => 'nullable|numeric|min:0',
            'status' => 'required|in:actif,conge,suspendu,demissionnaire,licencie',
            'emergency_contact_name' => 'nullable|string|max:150',
            'emergency_contact_phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1500',
        ]);

        $employee = Employee::create($validated);

        $prefix = $this->getRoutePrefix();

        return redirect()->route($prefix . 'employees.index')
            ->with('success', "Le personnel {$employee->full_name} a été enregistré avec succès (Matricule: {$employee->matricule}).");
    }

    /**
     * Affiche la fiche détaillée d'un membre du personnel.
     */
    public function show(Employee $personnel)
    {
        $personnel->load('jobPosition');
        $prefix = $this->getRoutePrefix();

        return view('admin.employees.show', compact('personnel', 'prefix'));
    }

    /**
     * Formulaire d'édition d'un employé.
     */
    public function edit(Employee $personnel)
    {
        $positions = JobPosition::active()->orderBy('title', 'asc')->get();
        $prefix = $this->getRoutePrefix();

        return view('admin.employees.edit', compact('personnel', 'positions', 'prefix'));
    }

    /**
     * Met à jour les informations d'un employé.
     */
    public function update(Request $request, Employee $personnel)
    {
        // Normaliser le salaire si saisi avec une virgule française
        if ($request->has('monthly_salary') && is_string($request->input('monthly_salary'))) {
            $request->merge([
                'monthly_salary' => str_replace(',', '.', trim($request->input('monthly_salary'))),
            ]);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'job_position_id' => 'nullable|exists:job_positions,id',
            'matricule' => 'nullable|string|max:50|unique:employees,matricule,' . $personnel->id,
            'gender' => 'nullable|in:M,F',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string|max:255',
            'national_id' => 'nullable|string|max:100',
            'contract_type' => 'nullable|string|max:50',
            'hired_at' => 'nullable|date',
            'monthly_salary' => 'nullable|numeric|min:0',
            'status' => 'required|in:actif,conge,suspendu,demissionnaire,licencie',
            'emergency_contact_name' => 'nullable|string|max:150',
            'emergency_contact_phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1500',
        ]);

        $personnel->update($validated);

        $prefix = $this->getRoutePrefix();

        return redirect()->route($prefix . 'employees.index')
            ->with('success', "Le dossier de {$personnel->full_name} a été mis à jour avec succès.");
    }

    /**
     * Supprime un employé du personnel.
     */
    public function destroy(Employee $personnel)
    {
        $name = $personnel->full_name;
        $personnel->delete();

        $prefix = $this->getRoutePrefix();

        return redirect()->route($prefix . 'employees.index')
            ->with('success', "L'employé {$name} a été retiré du personnel avec succès.");
    }
}
