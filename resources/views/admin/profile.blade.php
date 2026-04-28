@extends('layouts.dashboard')
@section('title', 'Profil Admin')
@section('page_title', 'Pengaturan Akun')
@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="space-y-6 animate-fade-in">
    <div class="max-w-2xl space-y-8">

        {{-- Page Header --}}
        <div class="flex items-center gap-4 mb-2">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary to-primary-container flex items-center justify-center shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[32px] text-white" style="font-variation-settings: 'FILL' 1;">person</span>
            </div>
            <div>
                <h1 class="text-2xl font-black text-primary">Profil Saya</h1>
                <p class="text-sm text-slate-500">Kelola informasi akun dan keamanan Anda.</p>
            </div>
        </div>

        {{-- Profile Information Card --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-40 h-40 bg-primary/5 rounded-br-full -z-0"></div>
            <div class="relative z-10 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px] text-primary">badge</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Informasi Profil</h2>
                        <p class="text-xs text-slate-400">Perbarui nama, email, dan nomor WhatsApp Anda.</p>
                    </div>
                </div>

                @if (session('success'))
                    <div class="p-3 mb-5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.profile.updateInfo') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full px-4 py-3 bg-surface border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm outline-none"
                            placeholder="Nama lengkap Anda" required>
                        @error('name')
                            <p class="text-xs text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-3 bg-surface border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm outline-none"
                            placeholder="email@contoh.com" required>
                        @error('email')
                            <p class="text-xs text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor WhatsApp</label>
                        <div class="relative">
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full px-4 py-3 bg-surface border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm outline-none pl-11"
                                placeholder="081234567890" required>
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">call</span>
                            </div>
                        </div>
                        @error('phone')
                            <p class="text-xs text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="px-6 py-3 rounded-xl bg-primary text-white font-bold text-sm hover:bg-primary-container transition-all shadow-md shadow-primary/20 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Password Card --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 relative overflow-hidden">
            <div class="absolute bottom-0 right-0 w-40 h-40 bg-primary/5 rounded-tl-full -z-0"></div>
            <div class="relative z-10 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px] text-amber-600">lock</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Ubah Kata Sandi</h2>
                        <p class="text-xs text-slate-400">Pastikan akun Anda menggunakan kata sandi yang kuat.</p>
                    </div>
                </div>

                @if (session('password_success'))
                    <div class="p-3 mb-5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        {{ session('password_success') }}
                    </div>
                @endif

                <form action="{{ route('admin.profile.updatePassword') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password"
                            class="w-full px-4 py-3 bg-surface border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm outline-none"
                            placeholder="••••••••" required>
                        @error('current_password')
                            <p class="text-xs text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kata Sandi Baru</label>
                            <input type="password" name="password"
                                class="w-full px-4 py-3 bg-surface border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm outline-none"
                                placeholder="••••••••" required>
                            @error('password')
                                <p class="text-xs text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Sandi Baru</label>
                            <input type="password" name="password_confirmation"
                                class="w-full px-4 py-3 bg-surface border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm outline-none"
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="px-6 py-3 rounded-xl bg-amber-500 text-white font-bold text-sm hover:bg-amber-600 transition-all shadow-md shadow-amber-500/20 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">lock_reset</span>
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Danger Zone --}}
        

    </div>
</div>
@endsection
