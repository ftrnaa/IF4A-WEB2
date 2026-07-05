
@extends('layouts.auth', [
    'title' => 'Daftar — Batix',
    'panelTitle' => 'Bergabung',
    'panelTitleItalic' => 'Bersama Kami',
    'panelDesc' => 'Buat akun gratis dan dapatkan akses ke ribuan motif batik AI.',
    'panelQuote' => '"Batik adalah identitas, bukan sekadar mode."',
    'panelQuoteAuthor' => '— Warisan Nusantara'
])
@section('content')

    {{-- Header --}}
    <p class="auth-form__eyebrow">Mulai perjalananmu</p>
    <h1 class="auth-form__title">Daftar <em>Gratis</em></h1>
    <p class="auth-form__subtitle">
        Sudah punya akun?
        <a href="/masuk">Masuk di sini</a>
    </p>

    {{-- Social Register --}}
    <div class="auth-social" style="margin-bottom:1.5rem">
        <a href="{{ route('google.login') }}" class="btn-social">
    <span class="btn-social__icon">G</span>
    Daftar dengan Google
</a>
    </div>

    {{-- Divider --}}
    <div class="auth-divider">atau daftar dengan email</div>

    {{-- Form --}}
    <form action="{{ route('register.process') }}"
      method="POST">
        @csrf

        {{-- Name Row --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="first_name">Nama Depan</label>
                <input
                    class="form-input"
                    type="text"
                    id="first_name"
                    name="first_name"
                    placeholder="Masukkan Nama Depan"
                    autocomplete="given-name"
                    required
                >
            </div>
            <div class="form-group">
                <label class="form-label" for="last_name">Nama Belakang</label>
                <input
                    class="form-input"
                    type="text"
                    id="last_name"
                    name="last_name"
                    placeholder="Masukkan Nama Belakang"
                    autocomplete="family-name"
                >
            </div>
        </div>

        {{-- Email --}}
<div class="form-group">
    <label class="form-label" for="email">Alamat Email</label>

    <input
        class="form-input @error('email') is-invalid @enderror"
        type="email"
        id="email"
        name="email"
        value="{{ old('email') }}"
        placeholder="nama@email.com"
        autocomplete="email"
        required
    >

    @error('email')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

        {{-- Password --}}
        <div class="form-group">
            <label class="form-label" for="password">Kata Sandi</label>
            <div class="form-input-wrap">
                <input
                    class="form-input"
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Minimal 8 karakter"
                    autocomplete="new-password"
                    required
                    oninput="checkStrength(this.value)"
                >
                <button type="button" class="toggle-pass" aria-label="Tampilkan sandi" onclick="togglePassword('password', this)">
                    👁
                </button>
            </div>
            {{-- Password Strength --}}
            <div class="pass-strength" id="pass-strength" style="display:none">
                <div class="pass-strength__bars">
                    <div class="pass-strength__bar" id="bar1"></div>
                    <div class="pass-strength__bar" id="bar2"></div>
                    <div class="pass-strength__bar" id="bar3"></div>
                    <div class="pass-strength__bar" id="bar4"></div>
                </div>
                <p class="pass-strength__label" id="strength-label">Terlalu lemah</p>
            </div>
        </div>

        {{-- Confirm Password --}}
        <div class="form-group">
            <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi</label>
            <div class="form-input-wrap">
                <input
                    class="form-input"
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Ulangi kata sandi"
                    autocomplete="new-password"
                    required
                >
                <button type="button" class="toggle-pass" aria-label="Tampilkan sandi" onclick="togglePassword('password_confirmation', this)">
                    👁
                </button>
            </div>
        </div>

       <div class="form-check">
    <input type="checkbox" id="terms" name="terms" required>

    <label for="terms">
        Saya setuju dengan

        <a href="javascript:void(0)"
           onclick="openModal('termsModal')">
            Syarat & Ketentuan
        </a>

        serta

        <a href="javascript:void(0)"
           onclick="openModal('privacyModal')">
            Kebijakan Privasi
        </a>

        Batix
    </label>
</div>

        {{-- Submit --}}
        <button type="submit" class="btn-auth">
            Daftar
        </button>

    </form>

    {{-- Back to home --}}
    <p style="text-align:center; margin-top:1.8rem; font-size:.8rem; color:var(--clr-text-muted)">
        <a href="/" style="color:var(--clr-brown); text-decoration:underline; text-underline-offset:2px">
            ← Kembali ke Beranda
        </a>
    </p>

    {{-- TERMS MODAL --}}
<div id="termsModal" class="custom-modal">
    <div class="custom-modal-content">

        <button class="modal-close" onclick="closeModal('termsModal')">
            &times;
        </button>

        <h2>📜 Syarat & Ketentuan Batix</h2>

        <p><strong>Terakhir diperbarui:</strong> Juni 2026</p>

        <h3>1. Penerimaan Ketentuan</h3>
        <p>
            Dengan membuat akun dan menggunakan layanan Batix,
            pengguna dianggap telah membaca, memahami,
            dan menyetujui seluruh syarat yang berlaku.
        </p>

        <h3>2. Deskripsi Layanan</h3>
        <p>
            Batix adalah platform digital yang menyediakan
            katalog, visualisasi, dan eksplorasi motif batik berbasis AI
            untuk tujuan edukasi, inspirasi desain, dan pelestarian budaya Indonesia.
        </p>

        <h3>3. Hak Kekayaan Intelektual</h3>
        <p>
            Seluruh logo, sistem, antarmuka, dan aset digital Batix
            dilindungi oleh hak cipta dan hukum yang berlaku.
        </p>

        <h3>4. Lisensi Penggunaan Motif</h3>
        <ul>
            <li>Menggunakan motif sebagai referensi dan pembelajaran.</li>
            <li>Menyimpan hasil visualisasi untuk penggunaan pribadi.</li>
            <li>Menggunakan motif yang berstatus bebas digunakan sesuai lisensinya.</li>
            <li>Dilarang mengklaim kepemilikan eksklusif atas motif tradisional.</li>
            <li>Dilarang menjual ulang aset Batix tanpa izin tertulis.</li>
        </ul>

        <h3>5. Akun Pengguna</h3>
        <p>
            Pengguna bertanggung jawab menjaga keamanan akun,
            email, dan kata sandi yang digunakan.
        </p>

        <h3>6. Pembatasan Tanggung Jawab</h3>
        <p>
            BatikAI tidak menjamin seluruh hasil rekomendasi AI
            bebas dari kesalahan atau sesuai untuk seluruh kebutuhan komersial.
        </p>

    </div>
</div>

{{-- PRIVACY MODAL --}}
<div id="privacyModal" class="custom-modal">
    <div class="custom-modal-content">

        <button class="modal-close" onclick="closeModal('privacyModal')">
            &times;
        </button>

        <h2>🔒 Kebijakan Privasi Batix</h2>

        <p><strong>Terakhir diperbarui:</strong> Juni 2026</p>

        <h3>1. Informasi yang Kami Kumpulkan</h3>
        <ul>
            <li>Nama depan dan nama belakang.</li>
            <li>Alamat email.</li>
            <li>Informasi akun Google (jika menggunakan Google Login).</li>
            <li>Riwayat penggunaan platform.</li>
        </ul>

        <h3>2. Penggunaan Informasi</h3>
        <p>
            Data digunakan untuk mengelola akun,
            meningkatkan kualitas layanan,
            dan memberikan pengalaman yang lebih personal.
        </p>

        <h3>3. Keamanan Data</h3>
        <p>
            BatikAI menerapkan perlindungan teknis dan administratif
            untuk menjaga keamanan data pengguna.
        </p>

        <h3>4. Pembagian Informasi</h3>
        <p>
            Batix tidak menjual data pengguna kepada pihak ketiga.
            Data hanya dibagikan apabila diwajibkan oleh hukum
            atau diperlukan untuk penyediaan layanan.
        </p>

        <h3>5. Hak Pengguna</h3>
        <ul>
            <li>Mengakses data pribadi.</li>
            <li>Memperbarui informasi akun.</li>
            <li>Menghapus akun sesuai ketentuan yang berlaku.</li>
        </ul>

        <h3>6. Kontak</h3>
        <p>
            Untuk pertanyaan terkait privasi,
            silakan menghubungi tim BatikAI melalui email resmi platform.
        </p>

    </div>
</div>

<style>
.custom-modal{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.55);
    backdrop-filter:blur(8px);
    z-index:9999;

    justify-content:center;
    align-items:center;
    padding:2rem;
}

.custom-modal-content{
    width:100%;
    max-width:850px;
    max-height:85vh;

    overflow-y:auto;

    background:#fff;

    border-radius:24px;

    padding:2rem 2.5rem;

    position:relative;

    box-shadow:0 20px 60px rgba(0,0,0,.2);
}

.custom-modal-content h2{
    margin-bottom:1rem;
    color:#7b4f28;
}

.custom-modal-content h3{
    margin-top:1.5rem;
    margin-bottom:.5rem;
}

.custom-modal-content p,
.custom-modal-content li{
    color:#555;
    line-height:1.8;
}

.custom-modal-content ul{
    padding-left:1.25rem;
}

.modal-close{
    position:absolute;
    top:15px;
    right:20px;

    background:none;
    border:none;

    font-size:32px;
    cursor:pointer;
}
</style>

<script>
function openModal(id)
{
    document.getElementById(id).style.display = "flex";
    document.body.style.overflow = "hidden";
}

function closeModal(id)
{
    document.getElementById(id).style.display = "none";
    document.body.style.overflow = "auto";
}

window.onclick = function(event)
{
    document.querySelectorAll('.custom-modal').forEach(function(modal){

        if(event.target === modal)
        {
            modal.style.display = "none";
            document.body.style.overflow = "auto";
        }

    });
}
</script>
@endsection