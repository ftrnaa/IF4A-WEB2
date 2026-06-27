@extends('layouts.app')

@push('styles')
    {{-- Pakai CSS koleksi agar card identik --}}
    <link rel="stylesheet" href="{{ asset('css/koleksi.css') }}">
    <style>
        /* Override grid untuk homepage: selalu 3 kolom di desktop */
        .collection .motif-grid {
            grid-template-columns: repeat(3, 1fr);
            margin-top: 0;
            padding-bottom: 0;
        }
        @media (max-width: 900px) {
            .collection .motif-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 520px) {
            .collection .motif-grid { grid-template-columns: repeat(1, 1fr); }
        }

        /* Card di homepage langsung visible (tanpa IntersectionObserver delay) */
        .collection .motif-card { opacity: 1; transform: none; }
    </style>
@endpush

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
                    <a href="{{ url('/koleksi') }}" class="btn btn-primary">
                        Lihat Koleksi
                        <span class="arrow">→</span>
                    </a>
                    <a href="{{ url('/tentang') }}" class="btn btn-ghost">
                        Pelajari lebih lanjut
                        <span class="arrow">→</span>
                    </a>
                </div>

                <div class="hero__stats fade-up fade-up-d3">
    <div>
        <p class="hero__stat-num">{{ number_format($totalMotif) }}+</p>
        <p class="hero__stat-lbl">Motif tersedia</p>
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
                        src="{{ asset('images/landing.jpeg') }}"
                        alt="Penenun batik dengan motif AI"
                        width="460"
                        height="520"
                    >
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
                    src="{{ asset('images/08.jpg') }}"
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
                        <span class="about__feature-icon"></span>
                        <span>Dilatih dari ratusan referensi batik otentik Nusantara</span>
                    </li>
                    <li class="about__feature">
                        <span class="about__feature-icon"></span>
                        <span>Motif baru dihasilkan setiap hari secara otomatis</span>
                    </li>
                    <li class="about__feature">
                        <span class="about__feature-icon"></span>
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
                <a href="{{ route('koleksi') }}" class="btn btn-ghost">
                    Lihat Selengkapnya <span class="arrow">→</span>
                </a>
            </div>

            <div class="motif-grid" id="homeMotifGrid">
                @forelse($motifs as $m)
               @php

    $styleName = \Illuminate\Support\Str::limit(
        ucfirst($m->kategori ?? 'Kontemporer'),
        35
    );

    $cardName = \Illuminate\Support\Str::limit(
        ucfirst($m->nama ?? 'Motif Batik'),
        45
    );

    // kode preview
    $fileCode = null;

    if (!empty($m->preview_image)) {

        preg_match(
            '/^(\d+)_/',
            $m->preview_image,
            $match
        );

        $fileCode = $match[1] ?? null;
    }

    // tanggal
    $tgl = '';

    if (!empty($m->api_created_at)) {

        try {

            $tgl = \Carbon\Carbon::parse($m->api_created_at)
                ->format('d M Y');

        } catch (\Exception $e) {}
    }

    // slideshow images
    $slides = [];

    if (!empty($m->preview_url)) {
        $slides[] = $m->preview_url;
    }

    if (!empty($m->costume_images) && is_array($m->costume_images)) {

        foreach ($m->costume_images as $c) {

            $slides[] = 'https://btx.agunghakase.my.id/api/image/' . $c;
        }
    }

    if (empty($slides)) {

        $slides[] = 'https://via.placeholder.com/300x230?text=No+Image';
    }

@endphp

    

                <a class="motif-card"
                   href="{{ route('detail', ['id' => $m->id]) }}"
                   data-kategori="{{ $m->kategori ?? 'semua' }}">

                    {{-- IMAGE SLIDESHOW --}}
                    <div class="card-image-wrap" data-slides="{{ json_encode($slides) }}">
                        @foreach($slides as $i => $src)
                            <img src="{{ $src }}"
                                 alt="{{ $cardName }}"
                                 class="slide-img{{ $i === 0 ? ' active' : '' }}"
                                 loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                        @endforeach

                        @if(count($slides) > 1)
                        <div class="slide-dots">
                            @foreach($slides as $i => $src)
                                <span class="dot{{ $i === 0 ? ' active' : '' }}"></span>
                            @endforeach
                        </div>
                        @endif

                        @if($fileCode)
                            <span class="card-code">#{{ $fileCode }}</span>
                        @endif
                        <span class="card-badge">Batik</span>
                    </div>

                    {{-- BODY --}}
                    <div class="card-body">
                        @if($styleName)
                            <p class="card-style">{{ $styleName }}</p>
                        @endif
                        <h3 class="card-title">{{ $cardName }}</h3>

                        <div class="card-divider"></div>

                        <div class="card-footer">
                            <p class="card-price">
    Rp 150.000
</p>
                            @if($tgl)
                                <span class="card-date">{{ $tgl }}</span>
                            @endif
                        </div>
                    </div>

                </a>
                @empty
                <p style="text-align:center; grid-column:1/-1; color:#9a8f80;">
                    Tidak ada data batik.
                </p>
                @endforelse
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

@endsection

@push('scripts')
<script>
/* Slideshow otomatis untuk card di homepage — logika sama persis dengan koleksi.js */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('#homeMotifGrid .motif-card').forEach((card) => {
        const wrap = card.querySelector('.card-image-wrap');
        if (!wrap) return;

        const imgs = wrap.querySelectorAll('.slide-img');
        const dots = wrap.querySelectorAll('.dot');
        if (imgs.length <= 1) return;

        let current = 0;

        function goTo(index) {
            imgs[current].classList.remove('active');
            dots[current]?.classList.remove('active');
            current = index % imgs.length;
            imgs[current].classList.add('active');
            dots[current]?.classList.add('active');
        }

        setInterval(() => goTo(current + 1), 2000);
    });
});
</script>
@endpush