@extends('layouts.app')

@section('content')
    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- HERO SECTION                                        --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <section class="hero section" aria-label="Hero">
        <div class="hero__pattern" aria-hidden="true"></div>

        <div class="container hero__inner">

            {{-- Content --}}
            <div class="hero__content">
                <p class="hero__eyebrow">
                    <span class="hero__eyebrow-dot" aria-hidden="true"></span>
                    Didukung Kecerdasan Buatan
                </p>

                <h1 class="hero__title fade-up">
                    Motif Batik<br>
                    <em>Tanpa Batas</em>
                </h1>

                <p class="hero__desc fade-up fade-up-d1">
                    Ribuan motif batik yang dihasilkan oleh AI, terinspirasi warisan
                    Nusantara. Pilih, lisensi, dan gunakan secara bebas tanpa hambatan.
                </p>

                <div class="hero__actions fade-up fade-up-d2">
                    <a href="#koleksi" class="btn btn-primary">
                        Lihat Koleksi
                        <span class="arrow">→</span>
                    </a>
                    <a href="#tentang" class="btn btn-ghost">
                        Pelajari lebih lanjut
                        <span class="arrow">→</span>
                    </a>
                </div>

                <div class="hero__stats fade-up fade-up-d3">
                    <div>
                        <p class="hero__stat-num">10.000+</p>
                        <p class="hero__stat-lbl">Motif tersedia</p>
                    </div>
                    <div>
                        <p class="hero__stat-num">500+</p>
                        <p class="hero__stat-lbl">Referensi otentik</p>
                    </div>
                    <div>
                        <p class="hero__stat-num">100%</p>
                        <p class="hero__stat-lbl">Bebas komersial</p>
                    </div>
                </div>
            </div>

            {{-- Visual --}}
            <div class="hero__visual" aria-hidden="true">
    <div class="hero__img-wrap">
        <img
            src="{{ asset('images/batik-artisan.png') }}"
            alt="Penenun batik dengan motif AI"
            width="460"
            height="520"
        >
                    
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- ABOUT / WARISAN SECTION                             --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <section class="about section" id="tentang" aria-label="Tentang Batika">
        <div class="container about__inner">

            {{-- Images --}}
            <div class="about__images" aria-hidden="true">
                <img
                    class="about__img about__img--main"
                    src =" {{ asset('images/08.jpg') }}"
                    alt="Detail motif batik"
                >
                <img
                    class="about__img about__img--accent"
                    src="{{ asset('images/10.jpg') }}"
                    alt="Kain batik Nusantara"
                >
            </div>

            {{-- Content --}}
            <div class="about__content">
                <span class="section-label">Tentang Batika</span>
                <h2 class="section-title">
                    Warisan Ditenun<br>
                    <span class="italic">Oleh Kecerdasan</span>
                </h2>

                <p class="about__body">
                    Batika menggabungkan keahlian motif batik Nusantara dengan teknologi
                    generatif AI. Setiap motif dalam platform kami terlatih dari ratusan
                    referensi batik otentik, kemudian dibangkitkan ulang menjadi karya
                    baru yang unik dan bebas digunakan secara komersial.
                </p>

                <ul class="about__features">
                    <li class="about__feature">
                        <span class="about__feature-icon">🎨</span>
                        <span>Dilatih dari ratusan referensi batik otentik Nusantara</span>
                    </li>
                    <li class="about__feature">
                        <span class="about__feature-icon">⚡</span>
                        <span>Motif baru dihasilkan setiap hari secara otomatis</span>
                    </li>
                    <li class="about__feature">
                        <span class="about__feature-icon">✅</span>
                        <span>Bebas digunakan untuk produk dan keperluan komersial</span>
                    </li>
                </ul>

                <a href="#" class="btn btn-ghost">
                    Pelajari misi kami
                    <span class="arrow">→</span>
                </a>
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- FEATURED COLLECTION                                 --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <section class="collection section" id="koleksi" aria-label="Koleksi Pilihan">
        <div class="container">

            <div class="collection__header">
                <div>
                    <span class="section-label">Pilihan Editor</span>
                    <h2 class="section-title">Koleksi <span class="italic">Pilihan</span></h2>
                </div>
                <a href="#" class="btn btn-ghost">
                    Lihat Selengkapnya <span class="arrow">→</span>
                </a>
            </div>

            <div class="collection__grid">

                {{-- Card 1 --}}
                <article class="product-card">
                    <a href="#">
                        <img class="product-card__img"
                             src="{{ asset('images/batik1.jpg') }}"
                             alt="Motif Sido Mukti" loading="lazy">
                        <div class="product-card__body">
                            <p class="product-card__category">Klasik</p>
                            <h3 class="product-card__name">Sido Mukti</h3>
                            <p class="product-card__price">Rp 120.000</p>
                        </div>
                    </a>
                </article>

                {{-- Card 2 --}}
                <article class="product-card">
                    <a href="#">
                        <img class="product-card__img"
                             src="{{ asset('images/batik3.jpg') }}"
                             alt="Motif Parang Rusak" loading="lazy">
                        <div class="product-card__body">
                            <p class="product-card__category">Pesisir</p>
                            <h3 class="product-card__name">Parang Rusak</h3>
                            <p class="product-card__price">Rp 95.000</p>
                        </div>
                    </a>
                </article>

                {{-- Card 3 --}}
                <article class="product-card">
                    <a href="#">
                        <img class="product-card__img"
                             src="{{ asset('images/batik4.jpg') }}"
                             alt="Motif Truntum" loading="lazy">
                        <div class="product-card__body">
                            <p class="product-card__category">Modern</p>
                            <h3 class="product-card__name">Truntum</h3>
                            <p class="product-card__price">Rp 150.000</p>
                        </div>
                    </a>
                </article>

                {{-- Card 4 --}}
                <article class="product-card">
                    <a href="#">
                        <img class="product-card__img"
                             src="{{ asset('images/batik5.jpg') }}"
                             alt="Motif Kawung" loading="lazy">
                        <div class="product-card__body">
                            <p class="product-card__category">Klasik</p>
                            <h3 class="product-card__name">Kawung</h3>
                            <p class="product-card__price">Rp 110.000</p>
                        </div>
                    </a>
                </article>

                {{-- Card 5 --}}
                <article class="product-card">
                    <a href="#">
                        <img class="product-card__img"
                             src="{{ asset('images/batik2.jpg') }}"
                             alt="Motif Mega Mendung" loading="lazy">
                        <div class="product-card__body">
                            <p class="product-card__category">Pesisir</p>
                            <h3 class="product-card__name">Mega Mendung</h3>
                            <p class="product-card__price">Rp 135.000</p>
                        </div>
                    </a>
                </article>

                {{-- Card 6 --}}
                <article class="product-card">
                    <a href="#">
                        <img class="product-card__img"
                             src="{{ asset('images/batik6.jpg') }}"
                             alt="Motif Sekar Jagad" loading="lazy">
                        <div class="product-card__body">
                            <p class="product-card__category">Kontemporer</p>
                            <h3 class="product-card__name">Sekar Jagad</h3>
                            <p class="product-card__price">Rp 175.000</p>
                        </div>
                    </a>
                </article>

            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- HOW IT WORKS                                        --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <section class="how section" aria-label="Cara Pakai">
        <div class="container">

            <div class="text-center" style="margin-bottom:var(--space-md)">
                <h2 class="section-title">
                    Browse, Lisensi,<br>
                    <span class="italic">Langsung Pakai</span>
                </h2>
                <p class="how__subtitle" style="margin-inline:auto">
                    Tiga langkah mudah untuk mendapatkan motif batik impianmu.
                </p>
            </div>

            <div class="how__steps">
                <x-step-card
                    number="01"
                    icon="🔍"
                    title="Browse & Temukan Motif"
                    desc="Jelajahi ribuan motif batik AI yang terinspirasi dari warisan Nusantara. Filter berdasarkan daerah, kategori, dan warna sesuai kebutuhanmu."
                />
                <x-step-card
                    number="02"
                    icon="📄"
                    title="Pilih Jenis Lisensi"
                    desc="Pilih lisensi yang sesuai — personal atau komersial. Semua motif kami bebas digunakan ulang dan dimodifikasi secara legal."
                />
                <x-step-card
                    number="03"
                    icon="⬇️"
                    title="Download dan Pakai"
                    desc="Unduh file berkualitas tinggi (SVG, PNG, PDF) dan langsung gunakan untuk produk, fashion, percetakan, atau kebutuhan digitalmu."
                />
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- CTA BANNER                                          --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <section class="cta" aria-label="Call to action">
        <div class="container cta__inner">
            <h2 class="section-title">
                Mulai Eksplorasi<br>
                <span class="italic">Warisan Nusantara</span>
            </h2>
            <p class="cta__desc">
                Bergabung dengan ribuan desainer, pengusaha, dan pencinta batik yang
                telah menggunakan Batika untuk karya terbaik mereka.
            </p>
            <div class="cta__actions">
                <a href="#" class="btn btn-cta-primary">
                    Mulai Gratis
                </a>
                <a href="#koleksi" class="btn btn btn-cta-ghost">
                    Lihat Koleksi
                </a>
            </div>
        </div>
    </section>

@endsection