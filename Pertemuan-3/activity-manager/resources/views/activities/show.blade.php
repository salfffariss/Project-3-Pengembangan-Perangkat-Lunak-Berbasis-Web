@extends('layouts.app')

@section('content')
    <p><a href="{{ route('activities.index') }}">← Kembali ke Daftar Kegiatan</a></p>

    <article class="card">
        <h1>{{ $activity->title }}</h1>
        <p><strong>Kode:</strong> {{ $activity->code }}</p>
        <p><strong>Periode:</strong> {{ $activity->start_at?->format('d M Y') }} s/d {{ $activity->end_at?->format('d M Y') }}</p>
        <p><strong>Lokasi:</strong> {{ $activity->location ?? '-' }}</p>
        <p><strong>Kapasitas:</strong> {{ $activity->capacity }} peserta</p>
        <p><strong>Kategori:</strong> {{ $activity->category?->name ?? '-' }}</p>
        <p><strong>Status:</strong> <span class="badge">{{ ucfirst($activity->status) }}</span></p>
        
        <h3>Deskripsi:</h3>
        <p>{{ $activity->description ?? 'Tidak ada deskripsi.' }}</p>

        <div style="display: flex; gap: 8px; margin-top: 20px;">
            @if ($activity->status === 'draft')
                <form action="{{ route('activities.publish', $activity) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn">Publish Kegiatan</button>
                </form>
            @elseif ($activity->status === 'published')
                <form action="{{ route('activities.complete', $activity) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary">Selesaikan Kegiatan</button>
                </form>
            @endif

            <a href="{{ route('activities.edit', $activity) }}" class="btn">Ubah</a>

            <form action="{{ route('activities.destroy', $activity) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Hapus</button>
            </form>
        </div>
    </article>
@endsection