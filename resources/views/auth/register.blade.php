@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center animate-fade-in px-4 py-12">
    <div class="w-full max-w-md">
        
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 p-8 border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-32 h-32 bg-primary/5 rounded-br-full -z-10"></div>
            
            <div class="text-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="VespaBox" class="h-12 w-auto mx-auto mb-6">
                <h1 class="text-2xl font-black text-primary mb-2">Buat Akun Baru</h1>
                <p class="text-gray-500 text-sm">Bergabung dengan VespaBox untuk kemudahan servis.</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                
                @if ($errors->any())
                    <div class="p-3 bg-red-100 border border-red-200 text-red-700 rounded-xl text-sm mb-4">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-3 bg-surface border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm outline-none" placeholder="John Doe" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 bg-surface border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm outline-none" placeholder="john@example.com" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor WhatsApp</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-3 bg-surface border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm outline-none" placeholder="081234567890" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kata Sandi</label>
                        <input type="password" name="password" class="w-full px-4 py-3 bg-surface border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm outline-none" placeholder="••••••••" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi</label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-3 bg-surface border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm outline-none" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-primary text-white font-bold text-sm hover:bg-primary-light transition-all shadow-md shadow-primary/20 mt-4">
                    Daftar Sekarang
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">Sudah punya akun? <a href="{{ route('login') }}" class="text-accent font-bold hover:underline">Masuk di sini</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
