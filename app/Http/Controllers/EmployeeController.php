<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\JobPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

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
     * Liste du personnel de Bio Farm Trading (exclut strictement les ouvriers).
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $positionId = $request->input('position_id');
        $status = $request->input('status');
        $contractType = $request->input('contract_type');

        // Répertoire exclusif du personnel permanent de Bio Farm Trading (hors ouvriers de production)
        $query = Employee::whereNull('worker_id')
            ->with('jobPosition')
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

        $perPage = (int) $request->input('per_page', 50);
        $employees = $query->paginate($perPage)->withQueryString();

        // Statistiques globales du personnel BFT (hors ouvriers)
        $totalStaff = Employee::whereNull('worker_id')->count();
        $activeStaff = Employee::whereNull('worker_id')->where('status', 'actif')->count();
        $onLeaveStaff = Employee::whereNull('worker_id')->where('status', 'conge')->count();
        $totalMonthlyPayroll = Employee::whereNull('worker_id')->where('status', 'actif')->sum('monthly_salary') ?? 0;

        // Postes disponibles pour les filtres et attributions avec nombre d'employés permanents
        $positions = JobPosition::active()
            ->withCount(['employees' => function ($q) {
                $q->whereNull('worker_id');
            }])
            ->orderBy('title', 'asc')
            ->get();

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
     * Exporte la liste officielle du personnel BFT au format PDF avec en-tête et logo (hors ouvriers).
     */
    public function exportPdf(Request $request)
    {
        $query = Employee::whereNull('worker_id')
            ->with('jobPosition')
            ->orderBy('last_name', 'asc')
            ->orderBy('first_name', 'asc');

        if ($request->filled('position_id')) {
            $query->where('job_position_id', $request->input('position_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('contract_type')) {
            $query->where('contract_type', $request->input('contract_type'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $employees = $query->get();

        $totalStaff = $employees->count();
        $activeStaff = $employees->where('status', 'actif')->count();
        $onLeaveStaff = $employees->where('status', 'conge')->count();
        $totalMonthlyPayroll = $employees->where('status', 'actif')->sum('monthly_salary') ?? 0;

        $pdf = \PDF::loadView('admin.employees.pdf', compact(
            'employees',
            'totalStaff',
            'activeStaff',
            'onLeaveStaff',
            'totalMonthlyPayroll'
        ))->setPaper('a4', 'landscape');

        $fileName = 'Liste_Personnel_BioFarmTrading_' . now()->format('d_m_Y') . '.pdf';

        return $pdf->download($fileName);
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
     * Modification rapide du poste d'un membre du personnel depuis la liste.
     */
    public function updatePosition(Request $request, Employee $personnel)
    {
        $validated = $request->validate([
            'job_position_id' => 'required|exists:job_positions,id',
        ]);

        $personnel->job_position_id = $validated['job_position_id'];
        $personnel->save();

        return redirect()->back()->with('success', "Le poste de {$personnel->full_name} a été mis à jour : {$personnel->jobPosition->title}.");
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
