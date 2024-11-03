@extends('layouts.starter')

@section('header-content')
    <h3>Request Inventory</h3>
@endsection

@section('main-content')
    <div class="container">
        <form action="{{ route('peminjaman.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="inventaris">Select Item:</label>
                <select name="id_inventaris" class="form-control" required>
                    @foreach($inventaris as $item)
                        <option value="{{ $item->id_inventaris }}">{{ $item->nama_barang }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="surat_peminjaman">Loan Letter (PDF):</label>
                <input type="file" name="surat_peminjaman" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="keterangan_peminjaman">Loan Details:</label>
                <textarea name="keterangan_peminjaman" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Request</button>
        </form>
    </div>
@endsection