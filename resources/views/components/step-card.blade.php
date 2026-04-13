@props([
    'number',   // string: "01", "02", "03"
    'icon',     // emoji or SVG string
    'title',    // string
    'desc',     // string
])

<div class="how__step">
    <p class="how__step-num">Langkah {{ $number }}</p>
    <div class="how__step-icon" aria-hidden="true">{{ $icon }}</div>
    <h3 class="how__step-title">{{ $title }}</h3>
    <p class="how__step-desc">{{ $desc }}</p>
</div>
