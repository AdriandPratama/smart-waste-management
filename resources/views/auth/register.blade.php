
@extends('layouts.guest')

@section('title', 'Register')

@section('content')

<div class="bg-white rounded-2xl shadow-lg p-8">

    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-green-700">
            Create Account
        </h1>
        <p class="text-gray-500 mt-2">
            Daftar akun Smart Waste Management
        </p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('register.process') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">
                Nama Lengkap
            </label>
            <input type="text" name="name"
                value="{{ old('name') }}"
                required
                class="w-full border rounded-lg px-4 py-3"
                placeholder="Nama lengkap">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">
                Email
            </label>
            <input type="email" name="email"
                value="{{ old('email') }}"
                required
                class="w-full border rounded-lg px-4 py-3"
                placeholder="Email">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">
                Password
            </label>
            <input type="password" name="password"
                required
                class="w-full border rounded-lg px-4 py-3"
                placeholder="Minimal 8 karakter">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">
                Konfirmasi Password
            </label>
            <input type="password" name="password_confirmation"
                required
                class="w-full border rounded-lg px-4 py-3"
                placeholder="Ulangi password">
        </div>

        <button type="submit"
            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg">
            Register
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Sudah punya akun?
        <a href="{{ route('login') }}"
            class="text-green-600 font-semibold">
            Login
        </a>
    </p>

</div>

@endsection
