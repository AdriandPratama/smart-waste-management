<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\WasteReport;
use App\Models\WasteCategory;
use Illuminate\Http\Request;
use App\Models\ReportStatusHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = WasteReport::with(['user', 'category'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = WasteCategory::orderBy('name')->get();

        return view('admin.reports.index', compact('reports', 'categories'));
    }


public function updateStatus(Request $request, string $id)
{
    $validated = $request->validate([
        'status' => 'required|in:pending,verified,in_progress,completed,rejected',
        'notes' => 'nullable|string|max:1000',
    ]);

    $report = WasteReport::findOrFail($id);

    DB::transaction(function () use ($report, $validated) {
        $oldStatus = $report->status;
        $newStatus = $validated['status'];

        if ($oldStatus === $newStatus) {
            return;
        }

        $report->update([
            'status' => $newStatus,
        ]);

        ReportStatusHistory::create([
            'report_id' => $report->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => Auth::id(),
            'notes' => $validated['notes'] ?? null,
        ]);
    });

    return back()->with('success', 'Status laporan berhasil diperbarui.');
}

}
