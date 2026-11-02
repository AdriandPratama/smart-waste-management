
@extends('layouts.guest')

@section('title', 'Login')

@section('content')

<div class="bg-white rounded-2xl shadow-lg p-8">

    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-green-700">
            Smart Waste Management
        </h1>
        <p class="text-gray-500 mt-2">
            Login untuk melanjutkan
        </p>
    </div>

    @if (session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">
        {{ session('success') }}
    </div>
@endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login.process') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">
                Email
            </label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none"
                placeholder="Masukkan email">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">
                Password
            </label>
            <input
                type="password"
                name="password"
                required
                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none"
                placeholder="Masukkan password">
        </div>

        <div class="mb-6 flex items-center gap-2">
            <input type="checkbox" name="remember" value="1">
            <label class="text-sm text-gray-600">
                Ingat saya
            </label>
        </div>

        <button
            type="submit"
            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg">
            Login
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Belum punya akun?
        <a href="{{ route('register') }}"
           class="text-green-600 font-semibold">
            Register
        </a>
    </p>

</div>

@endsection
