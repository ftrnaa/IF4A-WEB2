<?php
// app/Http/Controllers/User/ProfileController.php
// Fungsi: Menampilkan & mengupdate data profil, sandi, notifikasi, dan hapus akun

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    // ── 1. Tampilkan halaman profil ───────────────────────────────────────
    public function index()
    {
        return view('pages.users.profil', [
    'user' => Auth::user(),
]);
    }

    // ── 2. Simpan perubahan data pribadi + avatar ─────────────────────────
    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name'  => ['nullable', 'string', 'max:80'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'city'       => ['nullable', 'string', 'max:100'],
            'province'   => ['nullable', 'string', 'max:100'],
            'bio'        => ['nullable', 'string', 'max:500'],
            'avatar'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'first_name.required' => 'Nama depan wajib diisi.',
            'first_name.max'      => 'Nama depan maksimal 80 karakter.',
            'avatar.image'        => 'File harus berupa gambar.',
            'avatar.mimes'        => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'avatar.max'          => 'Ukuran gambar maksimal 2MB.',
            'bio.max'             => 'Bio maksimal 500 karakter.',
        ]);

        // Upload avatar baru jika ada
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama dari storage
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')
                                           ->store('avatars', 'public');
        }

        // Sync kolom 'name' agar konsisten
        $validated['name'] = trim(
            $validated['first_name'] . ' ' . ($validated['last_name'] ?? '')
        );

        $user->update($validated);

        return back()->with('success', 'Profil berhasil disimpan.');
    }

    // ── 3. Ubah kata sandi ────────────────────────────────────────────────
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'pass_current' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (! Hash::check($value, $user->password)) {
                        $fail('Sandi saat ini tidak sesuai.');
                    }
                },
            ],
            'pass_new'     => [
                'required',
                'string',
                Password::min(8),
                'different:pass_current',
            ],
            'pass_confirm' => ['required', 'same:pass_new'],
        ], [
            'pass_current.required' => 'Sandi saat ini wajib diisi.',
            'pass_new.required'     => 'Sandi baru wajib diisi.',
            'pass_new.different'    => 'Sandi baru harus berbeda dari sandi lama.',
            'pass_confirm.required' => 'Konfirmasi sandi wajib diisi.',
            'pass_confirm.same'     => 'Konfirmasi sandi tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($request->pass_new),
        ]);

        return back()->with('success_password', 'Kata sandi berhasil diubah.');
    }

    // ── 4. Simpan preferensi notifikasi ──────────────────────────────────
    public function updateNotifications(Request $request)
    {
        Auth::user()->update([
            'notif_license' => $request->boolean('notif_license'),
            'notif_cert'    => $request->boolean('notif_cert'),
            'notif_promo'   => $request->boolean('notif_promo'),
            'notif_news'    => $request->boolean('notif_news'),
        ]);

        return back()->with('success_notif', 'Preferensi notifikasi disimpan.');
    }

    // ── 5. Hapus akun permanen ────────────────────────────────────────────
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        // Hapus avatar dari storage jika ada
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Logout dulu sebelum hapus
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Hapus user dari database
        $user->delete();

        return redirect('/')
               ->with('info', 'Akun kamu telah dihapus. Terima kasih telah menggunakan BatikAI.');
    }
    
}