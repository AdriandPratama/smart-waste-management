<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\WasteReport;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $statistics = [
            'total' => WasteReport::count(),
            'pending' => WasteReport::where('status', 'pending')->count(),
            'verified' => WasteReport::where('status', 'verified')->count(),
            'in_progress' => WasteReport::where('status', 'in_progress')->count(),
            'completed' => WasteReport::where('status', 'completed')->count(),
            'rejected' => WasteReport::where('status', 'rejected')->count(),
            'users' => User::where('role', 'user')->count(),
        ];

        $latestReports = WasteReport::with(['user', 'category'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'statistics',
            'latestReports'
        ));
    }
}
