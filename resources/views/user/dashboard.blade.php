
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Smart Waste Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <nav class="bg-gray-900 text-white px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Smart Waste Management</h1>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-6">

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">
                    Dashboard User
                </h2>
                <p class="text-gray-500 mt-2">
                    Selamat datang, {{ auth()->user()->name }}
                </p>
            </div>

            <a href="{{ route('user.reports.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg font-semibold text-center">
                + Buat Laporan
            </a>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

            @php
                $cards = [
                    ['title' => 'Total Laporan', 'value' => $statistics['total'], 'color' => 'blue'],
                    ['title' => 'Pending', 'value' => $statistics['pending'], 'color' => 'yellow'],
                    ['title' => 'In Progress', 'value' => $statistics['in_progress'], 'color' => 'orange'],
                    ['title' => 'Completed', 'value' => $statistics['completed'], 'color' => 'green'],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-{{ $card['color'] }}-500">
                    <p class="text-gray-500 text-sm">{{ $card['title'] }}</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">
                        {{ $card['value'] }}
                    </h3>
                </div>
            @endforeach

        </div>

        <!-- Latest Reports -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">

            <div class="p-6 border-b">
                <h3 class="text-xl font-bold text-gray-800">
                    Riwayat Laporan Saya
                </h3>
                <p class="text-gray-500 text-sm mt-1">
                    Daftar laporan sampah yang pernah Anda buat
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-600 text-sm">
                        <tr>
                            <th class="px-6 py-4">Judul</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse ($latestReports as $report)
                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $report->title }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $report->category?->name ?? '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @switch($report->status)
                                            @case('pending') bg-yellow-100 text-yellow-700 @break
                                            @case('verified') bg-indigo-100 text-indigo-700 @break
                                            @case('in_progress') bg-orange-100 text-orange-700 @break
                                            @case('completed') bg-green-100 text-green-700 @break
                                            @case('rejected') bg-red-100 text-red-700 @break
                                            @default bg-gray-100 text-gray-700
                                        @endswitch">
                                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-gray-500 text-sm">
                                    {{ $report->created_at->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4">
                                    <a href="{{ route('user.reports.show', $report->id) }}"
                                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                        Detail
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10 text-gray-500">
                                    Anda belum memiliki laporan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </main>

</body>
</html>
