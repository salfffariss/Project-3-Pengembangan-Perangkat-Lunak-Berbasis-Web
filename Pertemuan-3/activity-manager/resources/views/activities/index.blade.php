{{-- 1. Mengambil kerangka dasar dari layouts/app.blade.php --}}
@extends('layouts.app')

{{-- 2. Memasukkan konten ini ke dalam @yield('content') --}}
@section('content')
    <h1>Daftar Kegiatan</h1>

    {{-- Perulangan menampilkan seluruh data kegiatan --}}
    @forelse ($activities as $activity)
        <article class="card">
            <h2>
                {{-- Klik judul akan membuka halaman detail kegiatan tersebut --}}
                <a href="{{ route('activities.show', $activity) }}">
                    {{ $activity->title }}
                </a>
            </h2>
            <p><strong>Tanggal:</strong> {{ $activity->activity_date->format('d M Y') }}</p>
            <p><strong>Kategori:</strong> {{ $activity->category }}</p>
            <span class="badge">Status: {{ $activity->status }}</span>
        </article>
    @empty
        {{-- Tampil hanya jika database masih kosong --}}
        <p>Belum ada kegiatan yang terdaftar.</p>
    @endforelse
@endsection