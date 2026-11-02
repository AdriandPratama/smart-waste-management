
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan Sampah</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="max-w-3xl mx-auto p-6">

        <a href="{{ route('user.dashboard') }}"
           class="text-green-700 hover:underline">
            &larr; Kembali ke Dashboard
        </a>

        <div class="bg-white rounded-xl shadow-sm p-8 mt-5">
            <h1 class="text-2xl font-bold text-gray-800">
                Buat Laporan Sampah
            </h1>

            <p class="text-gray-500 mt-2 mb-6">
                Isi informasi lokasi dan kondisi sampah yang ingin dilaporkan.
            </p>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-5">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.reports.store') }}"
      method="POST"
      enctype="multipart/form-data">
                @csrf

                <div class="mb-5">
                    <label class="block font-semibold mb-2">Judul Laporan</label>
                    <input type="text" name="title"
                        value="{{ old('title') }}"
                        placeholder="Contoh: Tumpukan sampah di pinggir jalan"
                        class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-green-500"
                        required>
                </div>

                <div class="mb-5">
                    <label class="block font-semibold mb-2">Kategori Sampah</label>
                    <select name="category_id"
                        class="w-full border rounded-lg p-3">
                        <option value="">Pilih kategori</option>

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    <label class="block font-semibold mb-2">Deskripsi</label>
                    <textarea name="description" rows="4"
                        placeholder="Jelaskan kondisi sampah..."
                        class="w-full border rounded-lg p-3">{{ old('description') }}</textarea>
                </div>


<div class="mb-6">
    <label class="block font-semibold mb-2">Lokasi Sampah</label>

    <button type="button" id="getLocation"
        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg">
        Gunakan Lokasi Saya
    </button>

    <p id="locationMessage" class="text-sm text-gray-500 mt-2">
        Klik tombol untuk mengambil lokasi Anda.
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-4">
        <div>
            <label class="block text-sm font-medium mb-2">Latitude</label>
            <input type="number" step="any" name="latitude" id="latitude"
                value="{{ old('latitude') }}"
                readonly
                class="w-full border rounded-lg p-3 bg-gray-100">
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Longitude</label>
            <input type="number" step="any" name="longitude" id="longitude"
                value="{{ old('longitude') }}"
                readonly
                class="w-full border rounded-lg p-3 bg-gray-100">
        </div>
    </div>
</div>

<div class="mb-6">
    <label class="block font-semibold mb-2">
        Foto Sampah
    </label>

    <input
        type="file"
        name="images[]"
        accept="image/jpeg,image/png,image/webp"
        multiple
        class="w-full border rounded-lg p-3"
    >

    <p class="text-sm text-gray-500 mt-2">
        Maksimal 5 gambar, masing-masing maksimal 5 MB.
        Format JPG, JPEG, PNG, atau WEBP.
    </p>
</div>

                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg">
                    Kirim Laporan
                </button>
            </form>
        </div>
    </div>




<script>
    const locationButton = document.getElementById('getLocation');
    const locationMessage = document.getElementById('locationMessage');

    locationButton.addEventListener('click', function () {
        if (!navigator.geolocation) {
            locationMessage.textContent =
                'Browser Anda tidak mendukung fitur lokasi.';
            return;
        }

        locationMessage.textContent = 'Sedang mengambil lokasi...';
        locationButton.disabled = true;

        navigator.geolocation.getCurrentPosition(
            function (position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                const accuracy = position.coords.accuracy;

                document.getElementById('latitude').value = latitude;
                document.getElementById('longitude').value = longitude;

                locationMessage.textContent =
                    `Lokasi berhasil ditemukan. Akurasi sekitar ±${Math.round(accuracy)} meter.`;

                locationButton.textContent = 'Ambil Ulang Lokasi';
                locationButton.disabled = false;
            },

            function (error) {
                let message = 'Gagal mengambil lokasi.';

                if (error.code === 1) {
                    message = 'Izin lokasi ditolak. Izinkan akses lokasi di browser.';
                } else if (error.code === 2) {
                    message = 'Lokasi tidak tersedia.';
                } else if (error.code === 3) {
                    message = 'Waktu pengambilan lokasi habis.';
                }

                locationMessage.textContent = message;
                locationButton.disabled = false;
            },

            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            }
        );
    });
</script>

</body>


</body>
</html>
