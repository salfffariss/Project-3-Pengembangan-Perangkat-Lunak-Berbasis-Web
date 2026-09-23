@extends('layouts.app')

@section('content')
    <p><a href="{{ route('activities.show', $activity) }}">← Batal & Kembali</a></p>

    <div class="card">
        <h1>Ubah Kegiatan</h1>

        <form action="{{ route('activities.update', $activity) }}" method="POST" novalidate>
            @csrf
            @method('PUT')
            @include('activities._form')
            <button type="submit" class="btn">Perbarui Kegiatan</button>
        </form>
    </div>
@endsection