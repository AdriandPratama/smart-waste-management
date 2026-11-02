<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\WasteReport;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $statistics = [
            'total' => WasteReport::where('user_id', $userId)->count(),
            'pending' => WasteReport::where('user_id', $userId)
                ->where('status', 'pending')->count(),
            'in_progress' => WasteReport::where('user_id', $userId)
                ->where('status', 'in_progress')->count(),
            'completed' => WasteReport::where('user_id', $userId)
                ->where('status', 'completed')->count(),
        ];

        $latestReports = WasteReport::with('category')
            ->where('user_id', $userId)
            ->latest()
            ->take(10)
            ->get();

        return view('user.dashboard', compact(
            'statistics',
            'latestReports'
        ));
    }
}
