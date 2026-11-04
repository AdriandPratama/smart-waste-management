<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClassificationController extends Controller
{
    public function classify(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        try {
            $image = $request->file('file');

            $response = Http::timeout(60)
                ->attach(
                    'file',
                    file_get_contents($image->getRealPath()),
                    $image->getClientOriginalName()
                )
                ->post(config('services.ml_api.url') . '/predict');

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ML API gagal memproses gambar.',
                    'detail' => $response->json(),
                ], 502);
            }

            return response()->json([
                'success' => true,
                'message' => 'Waste classification successful.',
                'data' => $response->json(),
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat terhubung ke ML API.',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }
}
