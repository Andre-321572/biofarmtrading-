<?php

namespace App\Http\Controllers;

use App\Models\JobPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobPositionController extends Controller
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
     * Liste des postes de travail.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $department = $request->input('department');

        $query = JobPosition::withCount('employees')
            ->orderBy('title', 'asc');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($department)) {
            $query->where('department', $department);
        }

        $positions = $query->paginate(15)->withQueryString();

        // Statistiques
        $totalPositions = JobPosition::count();
        $activePositions = JobPosition::where('is_active', true)->count();
        $totalAssignedStaff = \App\Models\Employee::whereNotNull('job_position_id')->count();

        // Liste des départements existants pour le filtre
        $departments = JobPosition::whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->pluck('department');

        $prefix = $this->getRoutePrefix();

        return view('admin.job_positions.index', compact(
            'positions',
            'departments',
            'totalPositions',
            'activePositions',
            'totalAssignedStaff',
            'search',
            'department',
            'prefix'
        ));
    }

    /**
     * Enregistre un nouveau poste.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'department' => 'nullable|string|max:150',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool)$request->input('is_active') : true;

        JobPosition::create($validated);

        $prefix = $this->getRoutePrefix();

        return redirect()->route($prefix . 'job_positions.index')
            ->with('success', "Le poste « {$validated['title']} » a été créé avec succès.");
    }

    /**
     * Met à jour un poste existant.
     */
    public function update(Request $request, JobPosition $poste)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'department' => 'nullable|string|max:150',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool)$request->input('is_active') : false;

        $poste->update($validated);

        $prefix = $this->getRoutePrefix();

        return redirect()->route($prefix . 'job_positions.index')
            ->with('success', "Le poste « {$poste->title} » a été mis à jour avec succès.");
    }

    /**
     * Supprime un poste.
     */
    public function destroy(JobPosition $poste)
    {
        $employeesCount = $poste->employees()->count();
        if ($employeesCount > 0) {
            return redirect()->back()->with('error', "Impossible de supprimer ce poste : {$employeesCount} employé(s) y sont actuellement rattaché(s). Veuillez d'abord réaffecter ces employés.");
        }

        $title = $poste->title;
        $poste->delete();

        $prefix = $this->getRoutePrefix();

        return redirect()->route($prefix . 'job_positions.index')
            ->with('success', "Le poste « {$title} » a été supprimé avec succès.");
    }
}
