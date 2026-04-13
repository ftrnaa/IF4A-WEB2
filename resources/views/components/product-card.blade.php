@props([
    'motif',          // array: id, name, category, price, image, slug
])

<article class="product-card" role="article">
    <a href="{{ route('collection.show', $motif['slug']) }}">

        {{-- Motif Image --}}
        <img
            class="product-card__img"
            src="{{ asset('storage/' . $motif['image']) }}"
            alt="Motif Batik {{ $motif['name'] }}"
            loading="lazy"
        >

        <div class="product-card__body">
            {{-- Category --}}
            <p class="product-card__category">{{ $motif['category'] }}</p>

            {{-- Name --}}
            <h3 class="product-card__name">{{ $motif['name'] }}</h3>

            {{-- Price --}}
            <p class="product-card__price">
                Rp {{ number_format($motif['price'], 0, ',', '.') }}
            </p>
        </div>

    </a>
</article>
