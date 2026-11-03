<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WasteReport;
use App\Models\WasteCategory;
use App\Models\ReportImage;
use App\Models\ReportStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WasteReportController extends Controller
{
    // Menampilkan semua laporan
    public function index()
    {
        $reports = WasteReport::with(['user', 'category', 'images'])
            ->latest()
            ->get();

        return response()->json($reports);
    }

    // Menampilkan laporan milik user yang sedang login
    public function myReports(Request $request)
    {
        $reports = WasteReport::with(['category', 'images'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($reports);
    }

    // Membuat laporan baru

public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category_id' => 'nullable|exists:waste_categories,id',
        'latitude' => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
    ]);

    $imagePath = null;

    try {
        // Simpan foto ke storage
        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')
                ->store('reports', 'public');
        }

        // Simpan laporan dan riwayat status
        $report = DB::transaction(function () use (
            $request,
            $validated,
            $imagePath
        ) {
            $report = WasteReport::create([
                'user_id' => $request->user()->id,
                'category_id' => $validated['category_id'] ?? null,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'status' => 'pending',
            ]);

            // Simpan informasi foto
            if ($imagePath) {
                $report->images()->create([
                    'image_path' => $imagePath,
                ]);
            }

            // Catat riwayat status
            ReportStatusHistory::create([
                'report_id' => $report->id,
                'old_status' => null,
                'new_status' => 'pending',
                'changed_by' => $request->user()->id,
                'notes' => 'Report created.',
            ]);

            return $report->load([
                'user',
                'category',
                'images',
            ]);
        });

        return response()->json([
            'message' => 'Waste report created successfully.',
            'data' => $report,
        ], 201);

    } catch (\Throwable $e) {
        // Hapus foto jika proses database gagal
        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        throw $e;
    }
}


    // Menampilkan detail laporan
    public function show(Request $request, WasteReport $wasteReport)
    {
        if (
            $request->user()->role !== 'admin' &&
            $wasteReport->user_id !== $request->user()->id
        ) {
            return response()->json([
                'message' => 'You do not have permission to access this report.',
            ], 403);
        }

        return response()->json(
            $wasteReport->load(['user', 'category', 'images', 'statusHistories.changedBy'])
        );
    }

    // Mengubah laporan
    public function update(Request $request, WasteReport $wasteReport)
    {
        if (
            $request->user()->role !== 'admin' &&
            $wasteReport->user_id !== $request->user()->id
        ) {
            return response()->json([
                'message' => 'You do not have permission to update this report.',
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:waste_categories,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $wasteReport->update($validated);

        return response()->json([
            'message' => 'Waste report updated successfully.',
            'data' => $wasteReport->fresh()->load(['user', 'category']),
        ]);
    }

    // Menghapus laporan
    public function destroy(Request $request, WasteReport $wasteReport)
    {
        if (
            $request->user()->role !== 'admin' &&
            $wasteReport->user_id !== $request->user()->id
        ) {
            return response()->json([
                'message' => 'You do not have permission to delete this report.',
            ], 403);
        }

        $wasteReport->delete();

        return response()->json([
            'message' => 'Waste report deleted successfully.',
        ]);
    }

    // Admin mengubah status laporan
    public function updateStatus(Request $request, WasteReport $wasteReport)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,verified,in_progress,completed,rejected',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $wasteReport, $validated) {
            $oldStatus = $wasteReport->status;

            $wasteReport->update([
                'status' => $validated['status'],
            ]);

            ReportStatusHistory::create([
                'report_id' => $wasteReport->id,
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
                'changed_by' => $request->user()->id,
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return response()->json([
            'message' => 'Report status updated successfully.',
            'data' => $wasteReport->fresh(),
        ]);
    }
}
