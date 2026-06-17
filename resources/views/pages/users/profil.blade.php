{{--
    resources/views/user/profile.blade.php

    PERBAIKAN vs versi lama:
    - Route names disesuaikan dengan web.php baru
    - Form hapus akun pakai @method('DELETE')
    - Avatar preview & sync ke form input tetap ada
    - Error box per-form sudah terpisah
--}}
@php
$user = $user ?? auth()->user();
@endphp

@extends('layouts.user-dashboard')
@section('title', 'Profil Saya — BatikAI')
@section('breadcrumb', 'Profil Saya')

@section('content')

<div class="admin-page-header">
    <h1>Profil Saya</h1>
    <p>Kelola informasi akun dan pengaturan pribadimu.</p>
</div>

{{-- ── Flash Toast ───────────────────────────────────────────────────────── --}}
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () =>
            userToast('✓ {{ session('success') }}'));
    </script>
@endif
@if(session('success_password'))
    <script>
        document.addEventListener('DOMContentLoaded', () =>
            userToast('🔑 {{ session('success_password') }}'));
    </script>
@endif
@if(session('success_notif'))
    <script>
        document.addEventListener('DOMContentLoaded', () =>
            userToast('🔔 {{ session('success_notif') }}'));
    </script>
@endif

<div class="user-grid-2">

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- KOLOM KIRI: Informasi Pribadi                                     --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div class="user-card">
        <div class="user-card__header">
            <p class="user-card__title">INFORMASI PRIBADI</p>
        </div>
        <div class="user-card__body">

            {{-- ── Avatar preview ──────────────────────────────────────── --}}
<div class="profile-avatar-section">

    <div class="profile-avatar-wrap">
        <img
            id="profile-avatar-img"
            src="{{ $user->avatar
                ? asset('storage/' . $user->avatar)
                : asset('images/default-avatar.png') }}"
            alt="Avatar">
            <style>
            .profile-avatar-wrap{
    width:90px;
    height:90px;
    position:relative;
    flex-shrink:0;
}

#profile-avatar-img{
    width:90px !important;
    height:90px !important;
    min-width:90px !important;
    min-height:90px !important;
    max-width:90px !important;
    max-height:90px !important;

    border-radius:50%;
    object-fit:cover;
    display:block;
}
</style>

        <label
            class="profile-avatar-edit"
            for="avatar"
            title="Ganti foto">
            ✏
        </label>
    </div>

    <div class="profile-avatar-info">
        <p class="profile-name">
            {{ $user->first_name }} {{ $user->last_name }}
        </p>

        <p class="profile-email">
            {{ $user->email }}
        </p>

        <span class="profile-member-since">
            ✦ Member sejak {{ $user->created_at->translatedFormat('M Y') }}
        </span>
    </div>

</div>

            {{-- ── Form Informasi Pribadi ───────────────────────────────── --}}
            <form class="profile-form"
                  method="POST"
                  action="{{ route('pages.users.profil.update-info') }}"
                  enctype="multipart/form-data">
                @csrf

                {{-- File avatar di dalam form (disync dari avatar-trigger via JS) --}}
                <input
                   type="file"
                   name="avatar"
                   id="avatar"
                   accept="image/*"
                   style="display:none"
                   onchange="previewAvatar(this)">

                <p class="profile-section-divider">Data Diri</p>

                {{-- Error box khusus form info --}}
                @if($errors->hasAny(['first_name','last_name','phone','city','province','bio','avatar']))
                    <div class="profile-error-box">
                        @foreach(['first_name','last_name','phone','city','province','bio','avatar'] as $f)
                            @error($f)<p>⚠ {{ $message }}</p>@enderror
                        @endforeach
                    </div>
                @endif

                {{-- Nama --}}
                <div class="profile-form-row">
                    <div class="profile-form-group">
                        <label class="profile-form-label" for="first_name">Nama Depan</label>
                        <input type="text" id="first_name" name="first_name"
                               class="profile-form-input @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name', $user->first_name) }}"
                               placeholder="Nama depan" required>
                        @error('first_name')
                            <p class="profile-field-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="profile-form-group">
                        <label class="profile-form-label" for="last_name">Nama Belakang</label>
                        <input type="text" id="last_name" name="last_name"
                               class="profile-form-input"
                               value="{{ old('last_name', $user->last_name) }}"
                               placeholder="Nama belakang (opsional)">
                    </div>
                </div>

                {{-- Email (read-only) --}}
                <div class="profile-form-group">
                    <label class="profile-form-label" for="email">Alamat Email</label>
                    <input type="email" id="email"
                           class="profile-form-input"
                           value="{{ $user->email }}" readonly
                           style="background:var(--clr-surface-2,#f5f5f5);cursor:not-allowed">
                    <p style="font-size:.72rem;color:var(--clr-text-muted);margin-top:.3rem">
                        Email tidak bisa diubah. Hubungi support jika perlu.
                    </p>
                </div>

                {{-- Telepon --}}
                <div class="profile-form-group">
                    <label class="profile-form-label" for="phone">Nomor Telepon</label>
                    <input type="tel" id="phone" name="phone"
                           class="profile-form-input"
                           placeholder="+62 812 xxxx xxxx"
                           value="{{ old('phone', $user->phone) }}">
                </div>

                {{-- Kota & Provinsi --}}
                <div class="profile-form-row">
                    <div class="profile-form-group">
                        <label class="profile-form-label" for="city">Kota</label>
                        <input type="text" id="city" name="city"
                               class="profile-form-input"
                               placeholder="Kota tinggal"
                               value="{{ old('city', $user->city) }}">
                    </div>
                    <div class="profile-form-group">
                        <label class="profile-form-label" for="province">Provinsi</label>
                        <select id="province" name="province" class="profile-form-select">
                            @php
                            $provinces = [
                                'Aceh','Sumatera Utara','Sumatera Barat','Riau',
                                'Kepulauan Riau','Jambi','Sumatera Selatan',
                                'Kepulauan Bangka Belitung','Bengkulu','Lampung',
                                'DKI Jakarta','Jawa Barat','Banten','Jawa Tengah',
                                'DI Yogyakarta','Jawa Timur','Bali',
                                'Nusa Tenggara Barat','Nusa Tenggara Timur',
                                'Kalimantan Barat','Kalimantan Tengah',
                                'Kalimantan Selatan','Kalimantan Timur','Kalimantan Utara',
                                'Sulawesi Utara','Gorontalo','Sulawesi Tengah',
                                'Sulawesi Barat','Sulawesi Selatan','Sulawesi Tenggara',
                                'Maluku','Maluku Utara','Papua Barat','Papua',
                                'Papua Selatan','Papua Tengah','Papua Pegunungan',
                            ];
                            $selected = old('province', $user->province);
                            @endphp
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov }}"
                                    {{ $selected === $prov ? 'selected' : '' }}>
                                    {{ $prov }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Bio --}}
                <div class="profile-form-group">
                    <label class="profile-form-label" for="bio">Bio Singkat</label>
                    <textarea id="bio" name="bio"
                              class="profile-form-textarea"
                              placeholder="Ceritakan sedikit tentang dirimu..."
                              style="min-height:80px"
                              maxlength="500">{{ old('bio', $user->bio) }}</textarea>
                    <p style="font-size:.72rem;color:var(--clr-text-muted);margin-top:.3rem">
                        Maksimal 500 karakter.
                    </p>
                </div>

                <div style="display:flex;justify-content:flex-end">
                    <button type="submit" class="btn-save">💾 Simpan Perubahan</button>
                </div>
            </form>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- KOLOM KANAN                                                        --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div style="display:flex;flex-direction:column;gap:1.2rem">

        {{-- ── Kartu Keamanan ────────────────────────────────────────── --}}
        <div class="user-card">
            <div class="user-card__header">
                <p class="user-card__title">KEAMANAN</p>
            </div>
            <div class="user-card__body">
                <form class="profile-form"
                      method="POST"
                      action="{{ route('pages.users.profil.update-password') }}">
                    @csrf

                    <p class="profile-section-divider">Ubah Kata Sandi</p>

                    @if($errors->hasAny(['pass_current','pass_new','pass_confirm']))
                        <div class="profile-error-box">
                            @foreach(['pass_current','pass_new','pass_confirm'] as $f)
                                @error($f)<p>⚠ {{ $message }}</p>@enderror
                            @endforeach
                        </div>
                    @endif

                    <div class="profile-form-group">
                        <label class="profile-form-label" for="pass_current">Sandi Saat Ini</label>
                        <div style="position:relative">
                            <input type="password" id="pass_current" name="pass_current"
                                   class="profile-form-input @error('pass_current') is-invalid @enderror"
                                   placeholder="••••••••"
                                   style="padding-right:2.8rem">
                            <button type="button" class="btn-eye"
                                    onclick="togglePass('pass_current',this)">👁</button>
                        </div>
                    </div>

                    <div class="profile-form-group">
                        <label class="profile-form-label" for="pass_new">Kata Sandi Baru</label>
                        <div style="position:relative">
                            <input type="password" id="pass_new" name="pass_new"
                                   class="profile-form-input @error('pass_new') is-invalid @enderror"
                                   placeholder="Minimal 8 karakter"
                                   style="padding-right:2.8rem">
                            <button type="button" class="btn-eye"
                                    onclick="togglePass('pass_new',this)">👁</button>
                        </div>
                    </div>

                    <div class="profile-form-group">
                        <label class="profile-form-label" for="pass_confirm">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" id="pass_confirm" name="pass_confirm"
                               class="profile-form-input @error('pass_confirm') is-invalid @enderror"
                               placeholder="Ulangi sandi baru">
                    </div>

                    <div style="display:flex;justify-content:flex-end">
                        <button type="submit" class="btn-save">🔑 Ubah Kata Sandi</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Kartu Notifikasi ───────────────────────────────────────── --}}
        <div class="user-card">
            <div class="user-card__header">
                <p class="user-card__title">NOTIFIKASI</p>
            </div>
            <div class="user-card__body">
                <form method="POST" action="{{ route('pages.users.profil.update-notif') }}">
                    @csrf
                    <div style="display:flex;flex-direction:column;gap:.85rem">
                        @php
                        $notifItems = [
                            ['key' => 'notif_license', 'label' => 'Lisensi hampir habis (30 hari)'],
                            ['key' => 'notif_cert',    'label' => 'Sertifikat baru diterima'],
                            ['key' => 'notif_promo',   'label' => 'Promo dan motif baru'],
                            ['key' => 'notif_news',    'label' => 'Newsletter BatikAI'],
                        ];
                        @endphp
                        @foreach($notifItems as $n)
                            <label style="display:flex;align-items:center;
                                          justify-content:space-between;
                                          cursor:pointer;font-size:.85rem;
                                          color:var(--clr-text-muted)">
                                <span>{{ $n['label'] }}</span>
                                <input type="checkbox"
                                       name="{{ $n['key'] }}"
                                       value="1"
                                       {{ $user->{$n['key']} ? 'checked' : '' }}
                                       style="width:18px;height:18px;
                                              accent-color:var(--clr-green);
                                              cursor:pointer">
                            </label>
                        @endforeach
                    </div>
                    <div style="display:flex;justify-content:flex-end;margin-top:1.1rem">
                        <button type="submit" class="btn-save"
                                style="padding:.55rem 1.3rem;font-size:.82rem">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Kartu Danger Zone ──────────────────────────────────────── --}}
        <div class="user-card">
            <div class="user-card__header">
                <p class="user-card__title" style="color:#C0392B">HAPUS AKUN</p>
            </div>
            <div class="user-card__body">
                <div class="danger-zone">
                    <div class="danger-zone__text">
                        <p>Hapus Akun</p>
                        <p>Akun dan semua data akan dihapus permanen. Tindakan ini tidak bisa dibatalkan.</p>
                    </div>
                   <form method="POST"
      action="{{ route('pages.users.profil.delete-account') }}"
      onsubmit="return confirm('Yakin ingin menghapus akun?\nTindakan ini TIDAK BISA dibatalkan.')">
    @csrf
    @method('DELETE')

    <button type="submit" class="btn-danger">
        Hapus Akun
    </button>
</form>
                </div>
            </div>
        </div>

    </div>{{-- end kolom kanan --}}

</div>{{-- end user-grid-2 --}}

@endsection

@push('scripts')
<script>
// ── Preview avatar sebelum upload ─────────────────────────────────────────────
function previewAvatar(triggerInput) {
    if (!triggerInput.files || !triggerInput.files[0]) return;

    const file = triggerInput.files[0];

    // Tampilkan preview langsung
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('profile-avatar-img').src = e.target.result;
    };
    reader.readAsDataURL(file);
}

// ── Toggle show/hide password ─────────────────────────────────────────────────
function togglePass(fieldId, btn) {
    const inp    = document.getElementById(fieldId);
    const hidden = inp.type === 'password';
    inp.type     = hidden ? 'text' : 'password';
    btn.textContent = hidden ? '🙈' : '👁';
}
</script>
@endpush