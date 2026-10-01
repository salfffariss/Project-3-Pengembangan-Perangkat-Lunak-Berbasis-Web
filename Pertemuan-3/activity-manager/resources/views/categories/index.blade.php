@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h1>Daftar Kategori</h1>
        <a href="{{ route('activities.index') }}" class="btn btn-secondary">← Kembali ke Kegiatan</a>
    </div>

    <table style="width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <thead>
            <tr style="background: #e5e7eb; text-align: left;">
                <th style="padding: 12px;">ID</th>
                <th style="padding: 12px;">Nama Kategori</th>
                <th style="padding: 12px;">Slug</th>
                <th style="padding: 12px;">Jumlah Kegiatan</th>
                <th style="padding: 12px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $cat)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">{{ $cat->id }}</td>
                    <td style="padding: 12px; font-weight: bold;">{{ $cat->name }}</td>
                    <td style="padding: 12px; color: #6b7280;">{{ $cat->slug }}</td>
                    <td style="padding: 12px;">
                        <span class="badge" style="background: {{ $cat->activities_count > 0 ? '#dbeafe' : '#f3f4f6' }}; color: {{ $cat->activities_count > 0 ? '#1e40af' : '#6b7280' }};">
                            {{ $cat->activities_count }} kegiatan
                        </span>
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <form action="{{ route('categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 4px 10px; font-size: 0.85rem;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 16px; text-align: center;">Belum ada data kategori.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
