@extends('layouts.starter')

@section('header-content')
@endsection

@section('main-content')
    <div class="container">
        <!-- Tombol Tambah Ruangan -->
{{--        <div class="mb-3">--}}
{{--            <a href="{{ route('ruangan.create') }}" class="btn btn-primary">Tambah Ruangan</a>--}}
{{--        </div>--}}

        <!-- Tabel Data Ruangan -->
        <h2>Daftar Ruangan</h2>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nama Ruangan</th>
                <th>Kapasitas</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($ruangan as $room)
                <tr>
                    <td>{{ $room->room_id }}</td>
                    <td>{{ $room->nama_ruangan }}</td>
                    <td>{{ $room->kapasitas }} orang</td>
                    <td>
                        <a href="" class="btn btn-warning btn-sm">Edit</a>
{{--                        {{ route('ruangan.edit', $room->room_id) }}--}}
                        <form action="" method="POST" style="display: inline;">
{{--                            {{ route('ruangan.destroy', $room->room_id) }}--}}
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Formulir Edit Ruangan -->
{{--        <h2>Edit Ruangan</h2>--}}
{{--        <form action="{{ route('ruangan.update', $room->room_id ?? '') }}" method="POST">--}}
{{--            @csrf--}}
{{--            @method('PUT')--}}
{{--            <div class="mb-3">--}}
{{--                <label for="nama_ruangan" class="form-label">Nama Ruangan</label>--}}
{{--                <input type="text" class="form-control" id="nama_ruangan" name="nama_ruangan" value="{{ $room->nama_ruangan ?? '' }}" required>--}}
{{--            </div>--}}
{{--            <div class="mb-3">--}}
{{--                <label for="kapasitas" class="form-label">Kapasitas</label>--}}
{{--                <input type="number" class="form-control" id="kapasitas" name="kapasitas" value="{{ $room->kapasitas ?? '' }}" required>--}}
{{--            </div>--}}
{{--            <button type="submit" class="btn btn-success">Simpan Perubahan</button>--}}
{{--        </form>--}}
    </div>
@endsection
