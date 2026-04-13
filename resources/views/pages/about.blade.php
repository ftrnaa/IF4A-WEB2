@extends('layouts.app')

@section('title', 'Tentang Kami — BatikAI')
@section('meta_desc', 'BatikAI menggabungkan kecerdasan buatan dengan warisan batik Nusantara untuk menciptakan ribuan motif baru yang bebas digunakan.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endpush

@section('content')

    {{-- ════════════════════════════════════════════════════ --}}
    {{-- HERO                                                 --}}
    {{-- ════════════════════════════════════════════════════ --}}
    <section class="about-hero" aria-label="Tentang BatikAI">
        <div class="about-hero__ring about-hero__ring--1" aria-hidden="true"></div>
        <div class="about-hero__ring about-hero__ring--2" aria-hidden="true"></div>

        <div class="container about-hero__inner">
            <p class="about-hero__eyebrow">
                <span class="about-hero__dot" aria-hidden="true"></span>
                Tentang Kami
            </p>
            <h1 class="about-hero__title">
                Teknologi yang Menghormati<br>
                <em>Warisan Leluhur</em>
            </h1>
            <p class="about-hero__desc">
                BatikAI lahir dari keyakinan bahwa kecerdasan buatan bisa menjadi
                jembatan — bukan pengganti — antara generasi masa kini dan kekayaan
                budaya Nusantara yang tak ternilai.
            </p>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════ --}}
    {{-- MISSION                                              --}}
    {{-- ════════════════════════════════════════════════════ --}}
    <section class="mission section" aria-label="Misi Kami">
        <div class="container mission__inner">

            <div class="mission__content">
                <span class="section-label">Misi & Visi</span>
                <h2 class="section-title">
                    Merawat Budaya<br>
                    <span class="italic">Lewat Teknologi</span>
                </h2>
                <p class="mission__body">
                    Kami percaya bahwa warisan batik Nusantara harus terus hidup dan
                    berkembang. BatikAI melatih model AI generatif dari ratusan motif
                    batik otentik yang telah didokumentasikan bersama para maestro batik
                    dari Yogyakarta, Solo, Pekalongan, Cirebon, dan daerah-daerah lainnya.
                    Hasilnya adalah motif-motif baru yang tetap berakar pada estetika
                    tradisional, namun segar dan bebas digunakan tanpa batasan komersial.
                </p>

                <div class="mission__values">
                    <div class="mission__value">
                        <div class="mission__value-icon">🏛️</div>
                        <div>
                            <p class="mission__value-title">Otentisitas</p>
                            <p class="mission__value-desc">Setiap motif terlatih dari referensi batik otentik yang dikurasi langsung bersama pengrajin dan ahli batik Nusantara.</p>
                        </div>
                    </div>
                    <div class="mission__value">
                        <div class="mission__value-icon">⚡</div>
                        <div>
                            <p class="mission__value-title">Aksesibilitas</p>
                            <p class="mission__value-desc">Motif berkualitas tinggi yang dulu hanya bisa didapat dari pengrajin kini bisa diakses oleh siapa saja, kapan saja.</p>
                        </div>
                    </div>
                    <div class="mission__value">
                        <div class="mission__value-icon">♻️</div>
                        <div>
                            <p class="mission__value-title">Keberlanjutan</p>
                            <p class="mission__value-desc">Sebagian pendapatan kami dialokasikan untuk mendukung komunitas pengrajin batik di seluruh Indonesia.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mission__visual" aria-hidden="true">
                <div class="mission__img-stack">
                    <img class="mission__img mission__img--1"
                         src="https://picsum.photos/seed/batikcraft1/600/500"
                         alt="Pengrajin batik">
                    <img class="mission__img mission__img--2"
                         src="https://picsum.photos/seed/batikcraft2/480/380"
                         alt="Motif batik detail">
                    <div class="mission__img-badge">
                        <p class="mission__img-badge-num">2022</p>
                        <p class="mission__img-badge-lbl">Berdiri sejak</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ════════════════════════════════════════════════════ --}}
    {{-- STATS BAR                                            --}}
    {{-- ════════════════════════════════════════════════════ --}}
    <section class="stats-bar" aria-label="Statistik">
        <div class="container">
            <div class="stats-bar__grid">
                <div class="stats-bar__item">
                    <p class="stats-bar__num">10.000+</p>
                    <p class="stats-bar__label">Motif Tersedia</p>
                </div>
                <div class="stats-bar__item">
                    <p class="stats-bar__num">500+</p>
                    <p class="stats-bar__label">Referensi Otentik</p>
                </div>
                <div class="stats-bar__item">
                    <p class="stats-bar__num">12.000+</p>
                    <p class="stats-bar__label">Pengguna Aktif</p>
                </div>
                <div class="stats-bar__item">
                    <p class="stats-bar__num">34</p>
                    <p class="stats-bar__label">Daerah Asal Motif</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════ --}}
    {{-- HOW WE WORK / PROSES                                 --}}
    {{-- ════════════════════════════════════════════════════ --}}
    <section class="process section" aria-label="Proses Kami">
        <div class="container">

            <div class="text-center" style="margin-bottom: var(--space-md)">
                <span class="section-label">Cara Kerja</span>
                <h2 class="section-title">
                    Dari Tradisi ke<br>
                    <span class="italic">Teknologi</span>
                </h2>
                <p class="section-lead" style="margin-inline:auto; margin-top:.8rem">
                    Bagaimana kami mengubah warisan batik menjadi motif digital berkualitas tinggi.
                </p>
            </div>

            <div class="process__timeline">

                <div class="process__item">
                    <div class="process__num">01</div>
                    <div class="process__content">
                        <h3 class="process__title">Dokumentasi & Kurasi Referensi</h3>
                        <p class="process__desc">
                            Tim kami bekerja langsung dengan para maestro batik dari berbagai
                            daerah di Indonesia untuk mendokumentasikan motif-motif otentik.
                            Lebih dari 500 motif unik telah dikurasi dan didigitalisasi
                            sebagai dataset pelatihan.
                        </p>
                    </div>
                </div>

                <div class="process__item">
                    <div class="process__num">02</div>
                    <div class="process__content">
                        <h3 class="process__title">Pelatihan Model AI Generatif</h3>
                        <p class="process__desc">
                            Model AI kami dilatih secara khusus menggunakan dataset batik yang
                            telah dikurasi. Berbeda dari model generik, model kami memahami
                            filosofi, pola geometris, dan estetika unik yang membedakan
                            setiap gaya batik daerah.
                        </p>
                    </div>
                </div>

                <div class="process__item">
                    <div class="process__num">03</div>
                    <div class="process__content">
                        <h3 class="process__title">Seleksi & Quality Control</h3>
                        <p class="process__desc">
                            Tidak semua motif yang dihasilkan AI langsung dipublikasikan.
                            Setiap motif melewati proses seleksi ketat oleh tim kurator
                            kami yang memastikan kualitas estetika dan keselarasan
                            dengan nilai-nilai tradisi.
                        </p>
                    </div>
                </div>

                <div class="process__item">
                    <div class="process__num">04</div>
                    <div class="process__content">
                        <h3 class="process__title">Publikasi & Lisensi Terbuka</h3>
                        <p class="process__desc">
                            Motif yang lolos seleksi dipublikasikan di platform dengan
                            lisensi yang jelas dan transparan. Pengguna dapat mengunduh,
                            memodifikasi, dan menggunakan motif untuk keperluan pribadi
                            maupun komersial.
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </section>

    {{-- ════════════════════════════════════════════════════ --}}
    {{-- TEAM                                                 --}}
    {{-- ════════════════════════════════════════════════════ --}}
    <section class="team section" aria-label="Tim Kami">
        <div class="container">

            <div class="text-center">
                <span class="section-label">Di Balik BatikAI</span>
                <h2 class="section-title">
                    Tim yang <span class="italic">Berdedikasi</span>
                </h2>
                <p class="section-lead" style="margin-inline:auto; margin-top:.8rem">
                    Gabungan teknolog, desainer, dan pecinta budaya yang percaya
                    tradisi bisa berjalan beriringan dengan inovasi.
                </p>
            </div>

            <div class="team__grid">

                <article class="team-card">
                    <div class="team-card__img-wrap">
                        <img class="team-card__img"
                             src="https://picsum.photos/seed/person1/400/400"
                             alt="Arif Wicaksono" loading="lazy">
                        <div class="team-card__overlay"></div>
                    </div>
                    <div class="team-card__body">
                        <h3 class="team-card__name">Arif Wicaksono</h3>
                        <p class="team-card__role">Co-Founder & CEO</p>
                        <p class="team-card__bio">
                            Berlatar belakang teknologi dan kecintaan mendalam pada seni
                            tradisional Indonesia. Arif memimpin visi BatikAI sejak hari pertama.
                        </p>
                    </div>
                </article>

                <article class="team-card">
                    <div class="team-card__img-wrap">
                        <img class="team-card__img"
                             src="https://picsum.photos/seed/person2/400/400"
                             alt="Sari Dewi Kusuma" loading="lazy">
                        <div class="team-card__overlay"></div>
                    </div>
                    <div class="team-card__body">
                        <h3 class="team-card__name">Sari Dewi Kusuma</h3>
                        <p class="team-card__role">Head of Cultural Research</p>
                        <p class="team-card__bio">
                            Ahli batik dan sejarah tekstil Nusantara. Sari memastikan
                            setiap motif yang kami hasilkan berakar pada nilai budaya yang sahih.
                        </p>
                    </div>
                </article>

                <article class="team-card">
                    <div class="team-card__img-wrap">
                        <img class="team-card__img"
                             src="https://picsum.photos/seed/person3/400/400"
                             alt="Rizky Pratama" loading="lazy">
                        <div class="team-card__overlay"></div>
                    </div>
                    <div class="team-card__body">
                        <h3 class="team-card__name">Rizky Pratama</h3>
                        <p class="team-card__role">Lead AI Engineer</p>
                        <p class="team-card__bio">
                            Spesialis model generatif dan computer vision. Rizky membangun
                            arsitektur AI yang menjadi jantung platform BatikAI.
                        </p>
                    </div>
                </article>

            </div>

        </div>
    </section>

    {{-- ════════════════════════════════════════════════════ --}}
    {{-- CTA                                                  --}}
    {{-- ════════════════════════════════════════════════════ --}}
    <section class="cta" aria-label="Call to action">
        <div class="container cta__inner">
            <h2 class="section-title">
                Bergabung dalam<br>
                <span class="italic">Perjalanan Ini</span>
            </h2>
            <p class="cta__desc">
                Bersama-sama kita jaga warisan batik Nusantara tetap hidup,
                relevan, dan bisa dinikmati oleh semua orang di seluruh dunia.
            </p>
            <div class="cta__actions">
                <a href="/daftar" class="btn btn-cta-primary">Mulai Gratis</a>
                <a href="/koleksi" class="btn btn-cta-ghost">Lihat Koleksi</a>
            </div>
        </div>
    </section>

@endsection