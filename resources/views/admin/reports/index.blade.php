
@extends('layouts.guest')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Manajemen Laporan</h1>
            <p class="text-gray-500">Cari dan filter seluruh laporan sampah.</p>
        </div>

        <a href="{{ route('admin.dashboard') }}"
           class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        @if (session('success'))
    <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-5">
        {{ session('success') }}
    </div>
@endif

        <form action="{{ route('admin.reports.index') }}"
              method="GET"
              class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Judul, nama, atau email pelapor..."
                   class="border rounded-lg p-3">

            <select name="status" class="border rounded-lg p-3">
                <option value="">Semua Status</option>
                <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                <option value="verified" @selected(request('status') == 'verified')>Verified</option>
                <option value="in_progress" @selected(request('status') == 'in_progress')>In Progress</option>
                <option value="completed" @selected(request('status') == 'completed')>Completed</option>
                <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
            </select>

            <select name="category_id" class="border rounded-lg p-3">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        @selected(request('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-4 py-2">
                    Filter
                </button>

                <a href="{{ route('admin.reports.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 rounded-lg px-4 py-2">
                    Reset
                </a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">Judul Laporan</th>
                        <th class="p-3">Pelapor</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($reports as $report)
                        <tr class="border-b">
                            <td class="p-3">
                                {{ $reports->firstItem() + $loop->index }}
                            </td>

                            <td class="p-3 font-medium">
                                {{ $report->title }}
                            </td>

                            <td class="p-3">
                                {{ $report->user?->name ?? '-' }}
                                <div class="text-sm text-gray-500">
                                    {{ $report->user?->email ?? '-' }}
                                </div>
                            </td>

                            <td class="p-3">
                                {{ $report->category?->name ?? '-' }}
                            </td>

                            <td class="p-3">
                                @php
                                    $statusLabels = [
                                        'pending' => 'Pending',
                                        'verified' => 'Verified',
                                        'in_progress' => 'In Progress',
                                        'completed' => 'Completed',
                                        'rejected' => 'Rejected',
                                    ];
                                @endphp

                                <span class="px-3 py-1 rounded-full text-sm bg-gray-100">
                                    {{ $statusLabels[$report->status] ?? $report->status }}
                                </span>
                            </td>

                            <td class="p-3">
                                {{ $report->created_at->format('d M Y') }}
                            </td>

                            <td class="p-3">
    <form action="{{ route('admin.reports.updateStatus', $report->id) }}"
          method="POST"
          class="space-y-2">

        @csrf
        @method('PUT')

        <select name="status"
                class="border rounded-lg p-2 w-full"
                required>

            <option value="pending" @selected($report->status == 'pending')>
                Pending
            </option>

            <option value="verified" @selected($report->status == 'verified')>
                Verified
            </option>

            <option value="in_progress" @selected($report->status == 'in_progress')>
                In Progress
            </option>

            <option value="completed" @selected($report->status == 'completed')>
                Completed
            </option>

            <option value="rejected" @selected($report->status == 'rejected')>
                Rejected
            </option>
        </select>

        <textarea name="notes"
                  rows="2"
                  placeholder="Catatan perubahan (opsional)"
                  class="border rounded-lg p-2 w-full"></textarea>

        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg w-full">
            Simpan Status
        </button>
    </form>
</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"
                                class="p-5 text-center text-gray-500">
                                Tidak ada laporan ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $reports->links() }}
        </div>

    </div>
</div>
@endsection
