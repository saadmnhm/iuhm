<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with('user')->latest();

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by title or user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('project_title', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $projects = $query->paginate(15);

        $statistics = [
            'total' => Project::count(),
            'pending' => Project::where('status', 'pending')->count(),
            'approved' => Project::where('status', 'approved')->count(),
            'rejected' => Project::where('status', 'rejected')->count(),
        ];

        return view('admin.projects.index', compact('projects', 'statistics'));
    }

    public function show($id)
    {
        $project = Project::with([
            'user',
            'products',
            'employees',
            'presentations',
            'deliveries',
            'equipment',
            'rawMaterials',
            'financials'
        ])->findOrFail($id);

        return view('admin.projects.show', compact('project'));
    }



    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $project = Project::findOrFail($id);
        $project->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Project status updated successfully!');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully!');
    }
}
