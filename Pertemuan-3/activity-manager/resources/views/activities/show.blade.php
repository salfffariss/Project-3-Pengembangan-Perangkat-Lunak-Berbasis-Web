@extends('layouts.app')

@section('content')
    <p><a href="{{ route('activities.index') }}">← Kembali ke Daftar Kegiatan</a></p>

    <article class="card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <h1>{{ $activity->title }}</h1>
            <span class="badge" style="background: #dbeafe; color: #1e40af; font-size: 0.95rem;">Kode: {{ $activity->code }}</span>
        </div>
        <p><strong>Periode:</strong> {{ $activity->start_at?->format('d M Y') }} s/d {{ $activity->end_at?->format('d M Y') }}</p>
        <p><strong>Lokasi:</strong> {{ $activity->location ?? '-' }}</p>
        <p><strong>Kapasitas:</strong> {{ $activity->capacity }} peserta</p>
        <p><strong>Kategori:</strong> {{ $activity->category?->name ?? '-' }}</p>
        <p><strong>Status:</strong> 
            <span class="badge" style="background: {{ $activity->status === 'published' ? '#d1fae5; color: #065f46;' : ($activity->status === 'completed' ? '#e0e7ff; color: #3730a3;' : '#fef3c7; color: #92400e;') }}">
                {{ ucfirst($activity->status) }}
            </span>
        </p>
        
        <h3>Deskripsi:</h3>
        <p>{{ $activity->description ?? 'Tidak ada deskripsi.' }}</p>

        <div style="display: flex; gap: 8px; margin-top: 24px; align-items: center; flex-wrap: wrap;">
            {{-- Tombol Transisi Status (Task 2) --}}
            @if ($activity->status === 'draft')
                <form action="{{ route('activities.publish', $activity) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn" style="background: #059669;">🚀 Publish Kegiatan</button>
                </form>
            @elseif ($activity->status === 'published')
                <form action="{{ route('activities.complete', $activity) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn" style="background: #4b5563;">✅ Selesaikan Kegiatan (Complete)</button>
                </form>
            @else
                <span class="badge" style="background: #e5e7eb; color: #374151;">Kegiatan Selesai (Completed)</span>
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