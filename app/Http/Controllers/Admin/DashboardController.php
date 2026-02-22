<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessPlan;
use App\Models\Candidat;
use App\Models\Address;
use App\Models\ProgrameList;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $statistics = [
            'total_projects' => BusinessPlan::count(),
            'pending_projects' => BusinessPlan::where('status', 'pending')->count(),
            'approved_projects' => BusinessPlan::where('status', 'approved')->count(),
            'rejected_projects' => BusinessPlan::where('status', 'rejected')->count(),
            'total_users' => User::count(),
            'recent_projects' => BusinessPlan::with('user')->latest()->take(10)->get(),
            'male_count' => Candidat::where('gender', 'male')->count(),
            'female_count' => Candidat::where('gender', 'female')->count(),
        ];

        $programe_list = ProgrameList::all();

        return view('admin.dashboard', compact('statistics', 'programe_list'));
    }

    public function createAddress(Request $request)
    {
        $validated = $request->validate([
            'address_line1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
        ]);

        Address::create($validated);

        return redirect()->back()->with('success', 'Address added successfully!');
    }
    public function DeleteAddess($id)
    {
        $address = Address::findOrFail($id);
        $address->delete();

        return redirect()->back()->with('success', 'Address deleted successfully!');
    }
}
