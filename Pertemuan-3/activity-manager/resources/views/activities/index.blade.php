@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h1>Daftar Kegiatan</h1>
        <a href="{{ route('activities.create') }}" class="btn">+ Tambah Kegiatan</a>
    </div>

    <!-- FORM SEARCH, FILTER & SORT (TASK 2) -->
    <div class="card" style="padding: 16px; margin-bottom: 20px;">
        <form method="GET" action="{{ route('activities.index') }}" style="display: flex; flex-direction: column; gap: 12px;">
            <div style="display: flex; gap: 8px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau judul kegiatan..." style="flex: 1; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;">
                <button type="submit" class="btn">Cari</button>
            </div>

            <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <div style="flex: 1; min-width: 150px;">
                    <label for="filter-category" style="font-size: 0.85rem; font-weight: bold; margin-bottom: 2px;">Kategori:</label>
                    <select name="category_id" id="filter-category" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="flex: 1; min-width: 140px;">
                    <label for="filter-status" style="font-size: 0.85rem; font-weight: bold; margin-bottom: 2px;">Status:</label>
                    <select name="status" id="filter-status" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                        <option value="published" @selected(request('status') === 'published')>Published</option>
                        <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    </select>
                </div>

                <div style="flex: 1; min-width: 140px;">
                    <label for="sort" style="font-size: 0.85rem; font-weight: bold; margin-bottom: 2px;">Urutkan Tanggal:</label>
                    <select name="sort" id="sort" onchange="this.form.submit()">
                        <option value="newest" @selected(request('sort') === 'newest')>Terbaru (Desc)</option>
                        <option value="oldest" @selected(request('sort') === 'oldest')>Terlama (Asc)</option>
                    </select>
                </div>

                @if(request()->hasAny(['search', 'category_id', 'status', 'sort']))
                    <div style="margin-top: 18px;">
                        <a href="{{ route('activities.index') }}" style="font-size: 0.85rem; color: #dc2626;">Reset Filter</a>
                    </div>
                @endif
            </div>
        </form>
    </div>

    <!-- DAFTAR KARTU KEGIATAN -->
    @forelse ($activities as $activity)
        <article class="card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <h2>
                    <a href="{{ route('activities.show', $activity) }}">
                        <span style="color: #2563eb; font-size: 0.9rem;">[{{ $activity->code }}]</span> {{ $activity->title }}
                    </a>
                </h2>
                <span class="badge" style="background: {{ $activity->status === 'published' ? '#d1fae5; color: #065f46;' : ($activity->status === 'completed' ? '#e0e7ff; color: #3730a3;' : '#fef3c7; color: #92400e;') }}">
                    {{ ucfirst($activity->status) }}
                </span>
            </div>
            <p><strong>Periode:</strong> {{ $activity->start_at?->format('d M Y') }} s/d {{ $activity->end_at?->format('d M Y') }}</p>
            <p><strong>Lokasi:</strong> {{ $activity->location ?? '-' }} | <strong>Kapasitas:</strong> {{ $activity->capacity }} peserta</p>
            <p><strong>Kategori:</strong> {{ $activity->category?->name ?? '-' }}</p>
        </article>
    @empty
        <p>Tidak ada kegiatan yang sesuai dengan filter.</p>
    @endforelse

    <!-- PAGINATION LINKS -->
    <div style="margin-top: 20px;">
        {{ $activities->links() }}
    </div>
@endsection