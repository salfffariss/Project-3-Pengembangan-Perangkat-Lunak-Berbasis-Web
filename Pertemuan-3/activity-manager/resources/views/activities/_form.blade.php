<div class="form-group">
    <label for="code">Kode Kegiatan</label>
    <input id="code" name="code" type="text" value="{{ old('code', $activity->code ?? '') }}" placeholder="Contoh: ACT-001">
    @error('code')
        <p class="error">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="title">Judul Kegiatan</label>
    <input id="title" name="title" type="text" value="{{ old('title', $activity->title ?? '') }}" placeholder="Masukkan judul kegiatan">
    @error('title')
        <p class="error">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="category_id">Kategori</label>
    <select id="category_id" name="category_id">
        <option value="">-- Pilih Kategori --</option>
        @foreach ($categories as $cat)
            <option value="{{ $cat->id }}" @selected(old('category_id', $activity->category_id ?? '') == $cat->id)>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')
        <p class="error">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="start_at">Tanggal Mulai</label>
    <input id="start_at" name="start_at" type="date" value="{{ old('start_at', isset($activity->start_at) ? $activity->start_at->format('Y-m-d') : '') }}">
    @error('start_at')
        <p class="error">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="end_at">Tanggal Selesai</label>
    <input id="end_at" name="end_at" type="date" value="{{ old('end_at', isset($activity->end_at) ? $activity->end_at->format('Y-m-d') : '') }}">
    @error('end_at')
        <p class="error">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="location">Lokasi Kegiatan</label>
    <input id="location" name="location" type="text" value="{{ old('location', $activity->location ?? '') }}" placeholder="Contoh: Gedung D4, Lab Komputer">
    @error('location')
        <p class="error">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="capacity">Kapasitas (Peserta)</label>
    <input id="capacity" name="capacity" type="number" min="1" max="500" value="{{ old('capacity', $activity->capacity ?? 100) }}">
    @error('capacity')
        <p class="error">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="description">Deskripsi</label>
    <textarea id="description" name="description" rows="3" placeholder="Deskripsi ringkas kegiatan...">{{ old('description', $activity->description ?? '') }}</textarea>
    @error('description')
        <p class="error">{{ $message }}</p>
    @enderror
</div>