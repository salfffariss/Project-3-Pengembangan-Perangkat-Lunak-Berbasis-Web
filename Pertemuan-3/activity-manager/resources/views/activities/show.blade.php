@extends('layouts.app')

@section('content')
    <p><a href="{{ route('activities.index') }}">← Kembali ke Daftar Kegiatan</a></p>

    <article class="card">
        <h1>{{ $activity->title }}</h1>
        <p><strong>Kode:</strong> {{ $activity->code }}</p>
        <p><strong>Periode:</strong> {{ $activity->start_at?->format('d M Y') }} s/d {{ $activity->end_at?->format('d M Y') }}</p>
        <p><strong>Lokasi:</strong> {{ $activity->location ?? '-' }}</p>
        <p><strong>Kapasitas:</strong> {{ $activity->capacity }} peserta (Terdaftar: {{ $activity->registered_count }})</p>
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

    @if ($activity->status === 'published')
        <section class="card" style="margin-top: 20px;">
            <h2>Pendaftaran Peserta</h2>
            <p><strong>Kuota:</strong> {{ $activity->registered_count }} dari {{ $activity->capacity }} peserta terdaftar</p>

            @if ($activity->start_at->isPast() && !$activity->start_at->isToday())
                <p style="color: #dc2626;">Pendaftaran telah ditutup karena kegiatan sudah dimulai atau telah lewat.</p>
            @elseif ($activity->registered_count >= $activity->capacity)
                <p style="color: #dc2626;">Kapasitas peserta untuk kegiatan ini sudah penuh.</p>
            @else
                <form action="{{ route('activities.register', $activity) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="participant_name">Nama Peserta</label>
                        <input id="participant_name" name="participant_name" type="text" value="{{ old('participant_name') }}" required>
                        @error('participant_name')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                        @error('email')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn">Daftar Kegiatan</button>
                </form>
            @endif
        </section>
    @endif
@endsection