@extends('layouts.app')

@section('content')
    <p><a href="{{ route('activities.index') }}">← Kembali ke Daftar Kegiatan</a></p>

    <article class="card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <h1>{{ $activity->title }}</h1>
            <span class="badge" style="background: #dbeafe; color: #1e40af; font-size: 0.95rem;">Kode: {{ $activity->code }}</span>
        </div>
        <p><strong>Tanggal:</strong> {{ $activity->activity_date->format('d M Y') }}</p>
        <p><strong>Kategori:</strong> {{ $activity->category?->name ?? '-' }}</p>
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