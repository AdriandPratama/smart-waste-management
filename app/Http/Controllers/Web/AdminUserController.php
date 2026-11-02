<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('role', 'user')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->withCount('wasteReports')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function destroy(string $id)
    {
        $user = User::where('role', 'user')
            ->with('wasteReports.images')
            ->findOrFail($id);

        // Hapus file foto laporan milik User
        foreach ($user->wasteReports as $report) {
            foreach ($report->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        // Data laporan dan relasinya mengikuti aturan foreign key database
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun User berhasil dihapus.');
    }
}
