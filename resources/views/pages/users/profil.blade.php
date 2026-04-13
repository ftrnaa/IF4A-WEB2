@extends('layouts.user-dashboard')
@section('title', 'Profil Saya — BatikAI')
@section('breadcrumb', 'Profil Saya')

@section('content')

<div class="admin-page-header">
    <h1>Profil Saya</h1>
    <p>Kelola informasi akun dan pengaturan pribadimu.</p>
</div>

<div class="user-grid-2">

    {{-- ── Profil Form ── --}}
    <div class="user-card">
        <div class="user-card__header">
            <p class="user-card__title">👤 Informasi Pribadi</p>
        </div>
        <div class="user-card__body">

            {{-- Avatar --}}
            <div class="profile-avatar-section">
                <div class="profile-avatar-wrap">
                    <img src="https://picsum.photos/seed/userme/200/200"
                         class="profile-avatar" alt="Foto profil" id="profile-avatar-img">
                    <label class="profile-avatar-edit" for="avatar-input" title="Ganti foto">✏</label>
                    <input type="file" id="avatar-input" accept="image/*" style="display:none"
                           onchange="previewAvatar(this)">
                </div>
                <div>
                    <p class="profile-name">Rina Susanti</p>
                    <p class="profile-email">rina@email.com</p>
                    <span class="profile-member-since">✦ Member sejak Apr 2025</span>
                </div>
            </div>

            <form class="profile-form" onsubmit="saveProfile(event)">

                <p class="profile-section-divider">Data Diri</p>

                <div class="profile-form-row">
                    <div class="profile-form-group">
                        <label class="profile-form-label" for="first_name">Nama Depan</label>
                        <input type="text" id="first_name" class="profile-form-input" value="Rina">
                    </div>
                    <div class="profile-form-group">
                        <label class="profile-form-label" for="last_name">Nama Belakang</label>
                        <input type="text" id="last_name" class="profile-form-input" value="Susanti">
                    </div>
                </div>

                <div class="profile-form-group">
                    <label class="profile-form-label" for="email">Alamat Email</label>
                    <input type="email" id="email" class="profile-form-input" value="rina@email.com" readonly>
                    <p style="font-size:.72rem;color:var(--clr-text-muted);margin-top:.3rem">Email tidak bisa diubah. Hubungi support jika perlu.</p>
                </div>

                <div class="profile-form-group">
                    <label class="profile-form-label" for="phone">Nomor Telepon</label>
                    <input type="tel" id="phone" class="profile-form-input" placeholder="+62 812 xxxx xxxx" value="+62 812 3456 7890">
                </div>

                <div class="profile-form-row">
                    <div class="profile-form-group">
                        <label class="profile-form-label" for="city">Kota</label>
                        <input type="text" id="city" class="profile-form-input" value="Jakarta">
                    </div>
                    <div class="profile-form-group">
                        <label class="profile-form-label" for="province">Provinsi</label>
                        <select id="province" class="profile-form-select">
                            <option>DKI Jakarta</option>
                            <option>Jawa Barat</option>
                            <option>Jawa Tengah</option>
                            <option>DI Yogyakarta</option>
                            <option>Jawa Timur</option>
                            <option>Bali</option>
                        </select>
                    </div>
                </div>

                <div class="profile-form-group">
                    <label class="profile-form-label" for="bio">Bio Singkat</label>
                    <textarea id="bio" class="profile-form-textarea"
                        placeholder="Ceritakan sedikit tentang dirimu..."
                        style="min-height:80px">Desainer tekstil yang mencintai warisan budaya Nusantara.</textarea>
                </div>

                <div style="display:flex;justify-content:flex-end">
                    <button type="submit" class="btn-save">💾 Simpan Perubahan</button>
                </div>

            </form>
        </div>
    </div>

    {{-- ── Kanan: Keamanan + Notifikasi + Bahaya ── --}}
    <div style="display:flex;flex-direction:column;gap:1.2rem">

        {{-- Keamanan --}}
        <div class="user-card">
            <div class="user-card__header">
                <p class="user-card__title">🔒 Keamanan</p>
            </div>
            <div class="user-card__body">
                <form class="profile-form" onsubmit="savePassword(event)">

                    <p class="profile-section-divider">Ubah Kata Sandi</p>

                    <div class="profile-form-group">
                        <label class="profile-form-label" for="pass_current">Sandi Saat Ini</label>
                        <div style="position:relative">
                            <input type="password" id="pass_current" class="profile-form-input"
                                   placeholder="••••••••" style="padding-right:2.8rem">
                            <button type="button" style="position:absolute;right:.7rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--clr-text-muted);font-size:.95rem"
                                    onclick="togglePassField('pass_current',this)">👁</button>
                        </div>
                    </div>

                    <div class="profile-form-group">
                        <label class="profile-form-label" for="pass_new">Sandi Baru</label>
                        <div style="position:relative">
                            <input type="password" id="pass_new" class="profile-form-input"
                                   placeholder="Minimal 8 karakter" style="padding-right:2.8rem">
                            <button type="button" style="position:absolute;right:.7rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--clr-text-muted);font-size:.95rem"
                                    onclick="togglePassField('pass_new',this)">👁</button>
                        </div>
                    </div>

                    <div class="profile-form-group">
                        <label class="profile-form-label" for="pass_confirm">Konfirmasi Sandi Baru</label>
                        <input type="password" id="pass_confirm" class="profile-form-input"
                               placeholder="Ulangi sandi baru">
                    </div>

                    <div style="display:flex;justify-content:flex-end">
                        <button type="submit" class="btn-save">🔑 Ubah Sandi</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Preferensi Notifikasi --}}
        <div class="user-card">
            <div class="user-card__header">
                <p class="user-card__title">🔔 Notifikasi</p>
            </div>
            <div class="user-card__body">
                <div style="display:flex;flex-direction:column;gap:.85rem">
                    @php
                    $notifs = [
                        ['id'=>'notif_license', 'label'=>'Lisensi hampir habis (30 hari)', 'checked'=>true],
                        ['id'=>'notif_cert',    'label'=>'Sertifikat baru diterima',        'checked'=>true],
                        ['id'=>'notif_promo',   'label'=>'Promo dan motif baru',             'checked'=>false],
                        ['id'=>'notif_news',    'label'=>'Newsletter BatikAI',               'checked'=>false],
                    ];
                    @endphp
                    @foreach($notifs as $n)
                    <label style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;font-size:.85rem;color:var(--clr-text-muted)">
                        <span>{{ $n['label'] }}</span>
                        <input type="checkbox" {{ $n['checked'] ? 'checked' : '' }}
                               style="width:18px;height:18px;accent-color:var(--clr-green);cursor:pointer">
                    </label>
                    @endforeach
                </div>
                <div style="display:flex;justify-content:flex-end;margin-top:1.1rem">
                    <button class="btn-save" style="padding:.55rem 1.3rem;font-size:.82rem"
                            onclick="userToast('✓ Preferensi notifikasi disimpan')">
                        Simpan
                    </button>
                </div>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="user-card">
            <div class="user-card__header">
                <p class="user-card__title" style="color:#C0392B">⚠ Zona Berbahaya</p>
            </div>
            <div class="user-card__body" style="display:flex;flex-direction:column;gap:.85rem">
                <div class="danger-zone">
                    <div class="danger-zone__text">
                        <p>Hapus Akun</p>
                        <p>Akun dan semua data akan dihapus permanen. Tindakan ini tidak bisa dibatalkan.</p>
                    </div>
                    <button class="btn-danger"
                            onclick="if(confirm('Yakin ingin menghapus akun?')) userToast('Permintaan dikirim ke support')">
                        Hapus Akun
                    </button>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection