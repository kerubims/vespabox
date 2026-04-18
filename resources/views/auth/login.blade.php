@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center animate-fade-in px-4">
    <div class="w-full max-w-md">
        
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 p-8 border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-accent/5 rounded-bl-full -z-10"></div>
            
            <div class="text-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="VespaBox" class="h-12 w-auto mx-auto mb-6">
                <h1 class="text-2xl font-black text-primary mb-2">Selamat Datang Kembali</h1>
                <p class="text-gray-500 text-sm">Masuk untuk mengelola booking servis Anda di VespaBox.</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                
                @if ($errors->any())
                    <div class="p-3 bg-red-100 border border-red-200 text-red-700 rounded-xl text-sm mb-4">
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 bg-surface border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm outline-none" placeholder="Masukkan email Anda" required>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block text-sm font-semibold text-gray-700">Kata Sandi</label>
                        <a href="#" class="text-xs text-accent font-semibold hover:text-accent-hover transition-colors">Lupa Kata Sandi?</a>
                    </div>
                    <input type="password" name="password" class="w-full px-4 py-3 bg-surface border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm outline-none" placeholder="••••••••" required>
                </div>
                
                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="remember" id="remember" class="rounded text-accent focus:ring-accent w-4 h-4 border-gray-300">
                    <label for="remember" class="text-sm text-gray-600 font-medium">Ingat Saya</label>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-primary text-white font-bold text-sm hover:bg-primary-light transition-all shadow-md shadow-primary/20 flex items-center justify-center gap-2 group mt-2">
                    Masuk Sekarang
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">Belum punya akun? <a href="{{ route('register') }}" class="text-accent font-bold hover:underline">Daftar di sini</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
