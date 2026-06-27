@extends('layouts.admin')

@section('title', 'Sinkronisasi Batik')
@section('breadcrumb', 'Sync Data')

@section('content')
<div class="sync-page">

    {{-- HEADER --}}
    <div class="sync-header">
        <div class="sync-header-left">
            <div class="sync-header-icon">
                <i class="ti ti-refresh"></i>
            </div>
            <div>
                <h1 class="sync-title">Sinkronisasi Data Batik</h1>
                <p class="sync-subtitle">Kelola dan pantau perbedaan data antara API dan database</p>
            </div>
        </div>
        <form action="{{ route('admin.sync.run') }}" method="POST" id="syncForm">
            @csrf
            <button type="submit" class="sync-btn" id="syncBtn">
                <i class="ti ti-rocket sync-btn-icon"></i>
                <span class="sync-btn-text">Sync Sekarang</span>
            </button>
        </form>
    </div>

    {{-- STATISTIK UTAMA --}}
    <div class="sync-grid">
        <div class="sync-card">
            <h3><i class="ti ti-database"></i> Database</h3>
            <p class="sync-value">{{ number_format($stats['database_total'] ?? 0) }}</p>
            <span>Produk tersimpan</span>
        </div>

        <div class="sync-card">
            <h3><i class="ti ti-cloud"></i> API</h3>
            <p class="sync-value">{{ number_format($stats['api_total'] ?? 0) }}</p>
            <span>Produk dari server</span>
        </div>

        <div class="sync-card sync-card-list">
            <h3><i class="ti ti-tags"></i> Kategori Database</h3>
            @if(!empty($stats['database_kategori']))
                <ul>
                    @foreach($stats['database_kategori'] as $key => $val)
                        <li>
                            <span>{{ ucfirst($key) }}</span>
                            <strong>{{ number_format($val) }}</strong>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="sync-empty">Belum ada data kategori</p>
            @endif
        </div>

        <div class="sync-card sync-card-list">
            <h3><i class="ti ti-tags"></i> Kategori API</h3>
            @if(!empty($stats['api_kategori']))
                <ul>
                    @foreach($stats['api_kategori'] as $key => $val)
                        <li>
                            <span>{{ ucfirst($key) }}</span>
                            <strong>{{ number_format($val) }}</strong>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="sync-empty">Belum ada data kategori</p>
            @endif
        </div>
    </div>

    {{-- HASIL SYNC --}}
    @if(session('sync_result'))
    <div class="sync-result">
        <h2><i class="ti ti-history"></i> Hasil Sinkronisasi Terakhir</h2>

        <div class="sync-result-grid">
            <div class="result-box green">
                <div class="result-icon"><i class="ti ti-plus"></i></div>
                <div>
                    <h4>Produk Baru</h4>
                    <p>{{ session('sync_result.inserted') }}</p>
                </div>
            </div>

            <div class="result-box orange">
                <div class="result-icon"><i class="ti ti-edit"></i></div>
                <div>
                    <h4>Diupdate</h4>
                    <p>{{ session('sync_result.updated') }}</p>
                </div>
            </div>

            <div class="result-box gray">
                <div class="result-icon"><i class="ti ti-minus"></i></div>
                <div>
                    <h4>Tidak Berubah</h4>
                    <p>{{ session('sync_result.unchanged') }}</p>
                </div>
            </div>
        </div>

        <p class="sync-time">
            <i class="ti ti-clock"></i> {{ session('sync_result.sync_time') }}
        </p>
    </div>
    @endif

</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.47.0/tabler-icons.min.css">
<link rel="stylesheet" href="{{ asset('css/sync.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/sync.js') }}"></script>
@endpush