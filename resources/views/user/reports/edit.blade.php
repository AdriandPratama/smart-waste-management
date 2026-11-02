
@extends('layouts.guest')

@section('content')
<div class="max-w-4xl mx-auto p-6">

    <a href="{{ route('user.reports.show', $report->id) }}"
       class="text-blue-600 hover:underline">
        &larr; Kembali ke Detail
    </a>

    <div class="bg-white rounded-xl shadow p-6 mt-5">

        <h1 class="text-2xl font-bold mb-6">
            Edit Laporan Sampah
        </h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-5">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.reports.update', $report->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="mb-5">
                <label class="block font-semibold mb-2">
                    Judul Laporan
                </label>
                <input type="text"
                       name="title"
                       value="{{ old('title', $report->title) }}"
                       required
                       class="w-full border rounded-lg p-3">
            </div>

            <div class="mb-5">
                <label class="block font-semibold mb-2">
                    Kategori Sampah
                </label>
                <select name="category_id"
                        class="w-full border rounded-lg p-3">
                    <option value="">Pilih Kategori</option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            @selected(old('category_id', $report->category_id) == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-5">
                <label class="block font-semibold mb-2">
                    Deskripsi
                </label>
                <textarea name="description"
                          rows="4"
                          class="w-full border rounded-lg p-3">{{ old('description', $report->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block font-semibold mb-2">
                        Latitude
                    </label>
                    <input type="number"
                           step="any"
                           name="latitude"
                           value="{{ old('latitude', $report->latitude) }}"
                           class="w-full border rounded-lg p-3">
                </div>

                <div>
                    <label class="block font-semibold mb-2">
                        Longitude
                    </label>
                    <input type="number"
                           step="any"
                           name="longitude"
                           value="{{ old('longitude', $report->longitude) }}"
                           class="w-full border rounded-lg p-3">
                </div>
            </div>


<div class="mb-5">
    <label class="block font-semibold mb-2">
        Foto Saat Ini
    </label>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        @forelse ($report->images as $image)
            <label class="relative cursor-pointer">
                <img
                    src="{{ asset('storage/' . $image->image_path) }}"
                    class="w-full h-40 object-cover rounded-lg"
                >

                <div class="mt-2 flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="delete_images[]"
                        value="{{ $image->id }}"
                    >
                    <span class="text-sm text-red-600">
                        Hapus foto ini
                    </span>
                </div>
            </label>
        @empty
            <p class="text-gray-500">Belum ada foto.</p>
        @endforelse
    </div>
</div>

<div class="mb-6">
    <label class="block font-semibold mb-2">
        Upload Foto Pengganti / Foto Baru
    </label>

    <input
        type="file"
        name="images[]"
        accept="image/jpeg,image/png,image/webp"
        multiple
        class="w-full border rounded-lg p-3"
    >

    <p class="text-sm text-gray-500 mt-2">
        Centang foto yang ingin dihapus, lalu upload foto penggantinya.
        Maksimal 5 foto setelah perubahan disimpan.
    </p>
</div>


            <div class="mb-6">
                <label class="block font-semibold mb-2">
                    Tambah Foto Baru
                </label>

                <input type="file"
                       name="images[]"
                       accept="image/jpeg,image/png,image/webp"
                       multiple
                       class="w-full border rounded-lg p-3">

                <p class="text-sm text-gray-500 mt-2">
                    Maksimal 5 foto secara keseluruhan.
                    Foto lama tidak akan dihapus.
                </p>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-semibold">
                    Simpan Perubahan
                </button>

                <a href="{{ route('user.reports.show', $report->id) }}"
                   class="bg-gray-200 hover:bg-gray-300 px-5 py-3 rounded-lg font-semibold">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
