
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Smart Waste Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white p-6 hidden md:block">
            <h1 class="text-xl font-bold mb-10">Smart Waste</h1>

            <nav class="space-y-3">
                <a href="{{ route('admin.dashboard') }}"
                   class="block bg-green-600 rounded-lg px-4 py-3">
                    Dashboard
                </a>

            <!--    <a href="#" class="block px-4 py-3 hover:bg-gray-700 rounded-lg">
                    Laporan Sampah
                </a> -->

              <a href="{{ route('admin.users.index') }}"
   class="block px-4 py-2 rounded-lg hover:bg-gray-100">
    Manajemen User
</a>

<a href="{{ route('admin.reports.index') }}"
   class="block px-4 py-2 rounded-lg hover:bg-gray-100">
    Manajemen Laporan
</a>

            </nav>

            <form action="{{ route('logout') }}" method="POST" class="mt-10">
                @csrf
                <button type="submit"
                    class="w-full text-left px-4 py-3 bg-red-600 hover:bg-red-700 rounded-lg">
                    Logout
                </button>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 md:p-10">

            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">
                        Admin Dashboard
                    </h2>
                    <p class="text-gray-500 mt-1">
                        Selamat datang, {{ auth()->user()->name }}
                    </p>
                </div>

                <span class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm font-semibold">
                    Administrator
                </span>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

                @php
                    $cards = [
                        ['title' => 'Total Laporan', 'value' => $statistics['total'], 'color' => 'blue'],
                        ['title' => 'Pending', 'value' => $statistics['pending'], 'color' => 'yellow'],
                        ['title' => 'Verified', 'value' => $statistics['verified'], 'color' => 'indigo'],
                        ['title' => 'In Progress', 'value' => $statistics['in_progress'], 'color' => 'orange'],
                        ['title' => 'Completed', 'value' => $statistics['completed'], 'color' => 'green'],
                        ['title' => 'Rejected', 'value' => $statistics['rejected'], 'color' => 'red'],
                        ['title' => 'Total User', 'value' => $statistics['users'], 'color' => 'purple'],
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
                        Laporan Terbaru
                    </h3>
                    <p class="text-gray-500 text-sm mt-1">
                        10 laporan terakhir yang masuk
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-600 text-sm">
                            <tr>
                                <th class="px-6 py-4">Judul</th>
                                <th class="px-6 py-4">Pelapor</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Tanggal</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @forelse ($latestReports as $report)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-800">
                                        {{ $report->title }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $report->user->name ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $report->category->name ?? '-' }}
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-10 text-gray-500">
                                        Belum ada laporan sampah.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </main>
    </div>

</body>
</html>
