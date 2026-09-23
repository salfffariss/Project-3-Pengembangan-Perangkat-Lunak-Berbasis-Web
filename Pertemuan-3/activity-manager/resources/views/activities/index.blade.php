@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h1>Daftar Kegiatan</h1>
        <a href="{{ route('activities.create') }}" class="btn">+ Tambah Kegiatan</a>
    </div>

    @forelse ($activities as $activity)
        <article class="card">
            <h2>
                <a href="{{ route('activities.show', $activity) }}">
                    {{ $activity->title }}
                </a>
            </h2>
            <p><strong>Tanggal:</strong> {{ $activity->activity_date->format('d M Y') }}</p>
            <p><strong>Kategori:</strong> {{ $activity->category }}</p>
            <span class="badge">Status: {{ $activity->status }}</span>
        </article>
    @empty
        <p>Belum ada kegiatan yang terdaftar.</p>
    @endforelse
@endsection