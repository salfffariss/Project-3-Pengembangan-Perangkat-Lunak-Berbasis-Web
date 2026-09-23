@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h1>Daftar Kegiatan</h1>
        <a href="{{ route('activities.create') }}" class="btn">+ Tambah Kegiatan</a>
    </div>

    <!-- FORM FILTER STATUS (INDEPENDENT CHALLENGE) -->
    <div class="card" style="padding: 12px; margin-bottom: 20px;">
        <form method="GET" action="{{ route('activities.index') }}" style="display: flex; gap: 10px; align-items: center;">
            <label for="filter-status" style="font-weight: bold; margin: 0;">Filter Status:</label>
            <select name="status" id="filter-status" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #d1d5db; width: auto;">
                <option value="">Semua Status</option>
                <option value="Planned" @selected(request('status') === 'Planned')>Planned</option>
                <option value="Ongoing" @selected(request('status') === 'Ongoing')>Ongoing</option>
                <option value="Done" @selected(request('status') === 'Done')>Done</option>
            </select>
            @if(request('status'))
                <a href="{{ route('activities.index') }}" style="font-size: 0.85rem; color: #dc2626;">Reset Filter</a>
            @endif
        </form>
    </div>

    <!-- DAFTAR KARTU KEGIATAN -->
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
        <p>Tidak ada kegiatan yang sesuai dengan filter.</p>
    @endforelse
@endsection