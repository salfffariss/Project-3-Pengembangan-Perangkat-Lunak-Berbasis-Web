@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h1>Daftar Kegiatan</h1>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('activities.trash') }}" class="btn btn-secondary">Sampah Kegiatan</a>
            <a href="{{ route('activities.create') }}" class="btn">+ Tambah Kegiatan</a>
        </div>
    </div>

    <!-- Filter & Pencarian (Task 2) -->
    <div class="card">
        <form method="GET" action="{{ route('activities.index') }}">
            <div class="form-group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau judul kegiatan...">
            </div>

            <div style="display: flex; gap: 10px; margin-bottom: 12px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 140px;">
                    <label for="filter-category">Kategori</label>
                    <select name="category_id" id="filter-category" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="flex: 1; min-width: 140px;">
                    <label for="filter-status">Status</label>
                    <select name="status" id="filter-status" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                        <option value="published" @selected(request('status') === 'published')>Published</option>
                        <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    </select>
                </div>

                <div style="flex: 1; min-width: 140px;">
                    <label for="sort">Urutkan</label>
                    <select name="sort" id="sort" onchange="this.form.submit()">
                        <option value="newest" @selected(request('sort') === 'newest')>Terbaru</option>
                        <option value="oldest" @selected(request('sort') === 'oldest')>Terlama</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 10px; align-items: center;">
                <button type="submit" class="btn">Filter</button>
                @if(request()->hasAny(['search', 'category_id', 'status', 'sort']))
                    <a href="{{ route('activities.index') }}" style="color: #dc2626; font-size: 0.85rem;">Reset Filter</a>
                @endif
            </div>
        </form>
    </div>

    <!-- DAFTAR KARTU KEGIATAN -->
    @forelse ($activities as $activity)
        <article class="card">
            <h2>
                <a href="{{ route('activities.show', $activity) }}">
                    [{{ $activity->code }}] {{ $activity->title }}
                </a>
            </h2>
            <p><strong>Periode:</strong> {{ $activity->start_at?->format('d M Y') }} s/d {{ $activity->end_at?->format('d M Y') }}</p>
            <p><strong>Lokasi:</strong> {{ $activity->location ?? '-' }} | <strong>Kapasitas:</strong> {{ $activity->capacity }} peserta</p>
            <p><strong>Kategori:</strong> {{ $activity->category?->name ?? '-' }}</p>
            <span class="badge">Status: {{ ucfirst($activity->status) }}</span>
        </article>
    @empty
        <p>Tidak ada kegiatan yang sesuai dengan filter.</p>
    @endforelse

    <!-- PAGINATION LINKS -->
    <div style="margin-top: 16px;">
        {{ $activities->links() }}
    </div>
@endsection