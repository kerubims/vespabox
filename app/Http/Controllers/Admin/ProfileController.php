<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        return view('admin.profile', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:15', Rule::unique('users')->ignore($user->id)],
        ], [
            'name.required'  => 'Nama lengkap wajib diisi.',
            'name.max'       => 'Nama tidak boleh lebih dari 255 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email'    => 'Masukkan alamat email yang valid.',
            'email.unique'   => 'Email ini sudah digunakan oleh akun lain.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.max'      => 'Nomor WhatsApp tidak boleh lebih dari 15 karakter.',
            'phone.unique'   => 'Nomor WhatsApp ini sudah digunakan oleh akun lain.',
        ]);

        $user->fill($validated);
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required'         => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password'  => 'Kata sandi saat ini yang Anda masukkan salah.',
            'password.required'                  => 'Kata sandi baru wajib diisi.',
            'password.min'                       => 'Kata sandi baru minimal harus 8 karakter.',
            'password.confirmed'                 => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('password_success', 'Kata sandi berhasil diperbarui.');
    }
}
