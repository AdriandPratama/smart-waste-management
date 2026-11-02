
@extends('layouts.guest')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <a href="{{ route('user.dashboard') }}"
       class="text-blue-600 hover:underline">
        &larr; Kembali ke Dashboard
    </a>

    <a href="{{ route('user.reports.edit', $report->id) }}"
   class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg mt-4">
    Edit Laporan
</a>

<form action="{{ route('user.reports.destroy', $report->id) }}"
      method="POST"
      class="inline-block mt-4"
      onsubmit="return confirm('Yakin ingin menghapus laporan ini? Data dan seluruh fotonya akan dihapus permanen.')">

    @csrf
    @method('DELETE')

    <button type="submit"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
        Hapus Laporan
    </button>
</form>

    <div class="bg-white rounded-xl shadow p-6 mt-5">
        <h1 class="text-2xl font-bold mb-4">
            {{ $report->title }}
        </h1>

        <p><strong>Status:</strong> {{ $report->status }}</p>

        <p><strong>Kategori:</strong>
            {{ $report->category?->name ?? 'Belum ditentukan' }}
        </p>

        <p><strong>Deskripsi:</strong></p>
        <p class="mb-4">
            {{ $report->description ?? '-' }}
        </p>

        <p><strong>Latitude:</strong> {{ $report->latitude ?? '-' }}</p>
        <p><strong>Longitude:</strong> {{ $report->longitude ?? '-' }}</p>

        <p class="mt-4 font-semibold">Foto Laporan:</p>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-3">
            @forelse ($report->images as $image)
                <img
                    src="{{ asset('storage/' . $image->image_path) }}"
                    alt="Foto laporan"
                    onclick="openImage(this.src)"
                    class="w-full h-48 object-cover rounded-lg cursor-pointer hover:opacity-80 transition"
                >
            @empty
                <p class="text-gray-500">Belum ada foto.</p>
            @endforelse
        </div>

        <p class="text-sm text-gray-500 mt-5">
            Dibuat pada: {{ $report->created_at->format('d M Y, H:i') }}
        </p>


<div class="mt-8 border-t pt-6">

    <h2 class="text-xl font-bold mb-5">
        Riwayat Status Laporan
    </h2>

    @php
        $statusLabels = [
            'pending' => 'Pending',
            'verified' => 'Verified',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'rejected' => 'Rejected',
        ];
    @endphp

    <div class="space-y-4">
        @forelse ($report->statusHistories as $history)

            <div class="border rounded-lg p-4">

                <div class="flex flex-wrap justify-between gap-2">
                    <div>
                        @if ($history->old_status)
                            <p class="font-semibold">
                                {{ $statusLabels[$history->old_status] ?? $history->old_status }}
                                &rarr;
                                {{ $statusLabels[$history->new_status] ?? $history->new_status }}
                            </p>
                        @else
                            <p class="font-semibold text-blue-600">
                                Laporan Dibuat
                            </p>
                            <p class="text-sm">
                                Status awal:
                                {{ $statusLabels[$history->new_status] ?? $history->new_status }}
                            </p>
                        @endif
                    </div>

                    <span class="text-sm text-gray-500">
                        {{ $history->created_at->format('d M Y, H:i') }}
                    </span>
                </div>

                <p class="text-sm text-gray-600 mt-2">
                    Diubah oleh:
                    {{ $history->changedBy?->name ?? 'Pengguna tidak tersedia' }}
                </p>

                @if ($history->notes)
                    <div class="bg-gray-50 rounded-lg p-3 mt-3">
                        <p class="text-sm font-semibold">Catatan:</p>
                        <p class="text-sm text-gray-700">
                            {{ $history->notes }}
                        </p>
                    </div>
                @endif

            </div>

        @empty
            <p class="text-gray-500">
                Belum ada riwayat status.
            </p>
        @endforelse
    </div>

</div>

    </div>
</div>

<!-- Image Preview Modal -->
<div id="imageModal"
     class="fixed inset-0 bg-black bg-opacity-90 hidden items-center justify-center z-50 p-4"
     onclick="closeImage()">

    <button
        type="button"
        onclick="closeImage()"
        class="absolute top-5 right-8 text-white text-4xl font-bold">
        &times;
    </button>

    <img
        id="previewImage"
        src=""
        alt="Preview gambar"
        onclick="event.stopPropagation()"
        class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl"
    >
</div>

<script>
    function openImage(src) {
        const modal = document.getElementById('imageModal');
        const preview = document.getElementById('previewImage');

        preview.src = src;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeImage() {
        const modal = document.getElementById('imageModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeImage();
        }
    });
</script>
@endsection
