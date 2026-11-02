<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\WasteReport;
use App\Models\WasteCategory;
use App\Models\ReportImage;
use App\Models\ReportStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserReportController extends Controller
{
    public function create()
    {
        $categories = WasteCategory::orderBy('name')->get();

        return view('user.reports.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:waste_categories,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $report = WasteReport::create([
                'user_id' => Auth::id(),
                'category_id' => $validated['category_id'] ?? null,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'status' => 'pending',
            ]);

            ReportStatusHistory::create([
                'report_id' => $report->id,
                'old_status' => null,
                'new_status' => 'pending',
                'changed_by' => Auth::id(),
                'notes' => 'Report created.',
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('reports', 'public');

                    ReportImage::create([
                        'report_id' => $report->id,
                        'image_path' => $path,
                    ]);
                }
            }
        });

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Laporan sampah berhasil dibuat.');
    }

public function show(string $id)
{
    $report = WasteReport::with([
        'user',
        'category',
        'images',
        'statusHistories' => function ($query) {
            $query->latest();
        },
        'statusHistories.changedBy',
    ])
    ->where('user_id', Auth::id())
    ->findOrFail($id);

    return view('user.reports.show', compact('report'));
}

public function edit(string $id)
{
    $report = WasteReport::with('images')
        ->where('user_id', Auth::id())
        ->findOrFail($id);

    $categories = WasteCategory::orderBy('name')->get();

    return view('user.reports.edit', compact('report', 'categories'));
}


public function update(Request $request, string $id)
{
    $report = WasteReport::where('user_id', Auth::id())
        ->findOrFail($id);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category_id' => 'nullable|exists:waste_categories,id',
        'latitude' => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
        'images' => 'nullable|array',
        'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        'delete_images' => 'nullable|array',
        'delete_images.*' => 'integer|exists:report_images,id',
    ]);

    $deleteIds = $validated['delete_images'] ?? [];
    $newImages = $request->file('images', []);

    $existingCount = $report->images()->count();
    $remainingCount = $existingCount - count(
        $report->images()->whereIn('id', $deleteIds)->pluck('id')
    );

    if ($remainingCount + count($newImages) > 5) {
        return back()
            ->withInput()
            ->withErrors([
                'images' => 'Total gambar laporan maksimal 5 foto.'
            ]);
    }

    DB::transaction(function () use (
        $report, $validated, $deleteIds, $newImages
    ) {
        $report->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
        ]);

        // Hapus foto yang dipilih
        $imagesToDelete = $report->images()
            ->whereIn('id', $deleteIds)
            ->get();

        foreach ($imagesToDelete as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        // Simpan foto baru
        foreach ($newImages as $image) {
            $path = $image->store('reports', 'public');

            ReportImage::create([
                'report_id' => $report->id,
                'image_path' => $path,
            ]);
        }
    });

    return redirect()
        ->route('user.reports.show', $report->id)
        ->with('success', 'Laporan berhasil diperbarui.');
}


public function destroy(string $id)
{
    $report = WasteReport::with('images')
        ->where('user_id', Auth::id())
        ->findOrFail($id);

    DB::transaction(function () use ($report) {
        // Hapus seluruh file foto dari storage
        foreach ($report->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Hapus laporan beserta data terkait
        $report->delete();
    });

    return redirect()
        ->route('user.dashboard')
        ->with('success', 'Laporan berhasil dihapus.');
}


}
