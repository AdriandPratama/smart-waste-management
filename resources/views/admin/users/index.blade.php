
@extends('layouts.guest')

@section('content')
<div class="max-w-6xl mx-auto p-6">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Manajemen User</h1>
            <p class="text-gray-500">Kelola akun pengguna Smart Waste Management.</p>
        </div>

        <a href="{{ route('admin.dashboard') }}"
           class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg">
            Kembali
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-5">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow p-6">

        <form action="{{ route('admin.users.index') }}"
              method="GET"
              class="flex gap-3 mb-6">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama atau email..."
                   class="flex-1 border rounded-lg p-3">

            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 rounded-lg">
                Cari
            </button>

            <a href="{{ route('admin.users.index') }}"
               class="bg-gray-200 hover:bg-gray-300 px-5 py-3 rounded-lg">
                Reset
            </a>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">Nama</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Total Laporan</th>
                        <th class="p-3">Tanggal Daftar</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-b">
                            <td class="p-3">
                                {{ $users->firstItem() + $loop->index }}
                            </td>

                            <td class="p-3 font-medium">
                                {{ $user->name }}
                            </td>

                            <td class="p-3">
                                {{ $user->email }}
                            </td>

                            <td class="p-3">
                                {{ $user->waste_reports_count }}
                            </td>

                            <td class="p-3">
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            <td class="p-3">
                                <form action="{{ route('admin.users.destroy', $user->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus akun ini beserta seluruh laporan dan fotonya?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="p-5 text-center text-gray-500">
                                Tidak ada User ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $users->links() }}
        </div>

    </div>
</div>
@endsection
