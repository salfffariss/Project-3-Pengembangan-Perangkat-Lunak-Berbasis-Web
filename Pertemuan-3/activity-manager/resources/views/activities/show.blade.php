@extends('layouts.app')

@section('content')
    <p><a href="{{ route('activities.index') }}">← Kembali ke Daftar Kegiatan</a></p>

    <article class="card">
        <h1>{{ $activity->title }}</h1>
        <p><strong>Tanggal:</strong> {{ $activity->activity_date->format('d M Y') }}</p>
        <p><strong>Kategori:</strong> {{ $activity->category }}</p>
        <p><strong>Status:</strong> <span class="badge">{{ $activity->status }}</span></p>
        
        <h3>Deskripsi:</h3>
        <p>{{ $activity->description ?? 'Tidak ada deskripsi.' }}</p>

        <div style="display: flex; gap: 8px; margin-top: 20px;">
            <a href="{{ route('activities.edit', $activity) }}" class="btn">Ubah</a>

            <form action="{{ route('activities.destroy', $activity) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Hapus</button>
            </form>
        </div>
    </article>
@endsection