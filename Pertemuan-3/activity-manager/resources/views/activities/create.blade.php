@extends('layouts.app')

@section('content')
    <p><a href="{{ route('activities.index') }}">← Batal & Kembali</a></p>

    <div class="card">
        <h1>Tambah Kegiatan Baru</h1>

        <form action="{{ route('activities.store') }}" method="POST" novalidate>
            @csrf
            @include('activities._form')
            <button type="submit" class="btn">Simpan Kegiatan</button>
        </form>
    </div>
@endsection