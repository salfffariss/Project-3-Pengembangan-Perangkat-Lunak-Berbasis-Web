@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h1>Sampah Kegiatan</h1>
        <a href="{{ route('activities.index') }}" class="btn btn-secondary">Kembali ke Daftar Kegiatan</a>
    </div>

    @forelse ($activities as $activity)
        <article class="card">
            <h2>[{{ $activity->code }}] {{ $activity->title }}</h2>
            <p><strong>Kategori:</strong> {{ $activity->category?->name ?? '-' }}</p>
            <p><strong>Dihapus pada:</strong> {{ $activity->deleted_at?->format('d M Y H:i') }}</p>

            <div style="margin-top: 14px;">
                <form action="{{ route('activities.restore', $activity->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn">Pulihkan (Restore)</button>
                </form>
            </div>
        </article>
    @empty
        <div class="card">
            <p>Tidak ada kegiatan di dalam tempat sampah.</p>
        </div>
    @endforelse

    <div style="margin-top: 16px;">
        {{ $activities->links() }}
    </div>
@endsection
