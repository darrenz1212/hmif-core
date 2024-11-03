@extends('layouts.starter')

@section('header-content')
    <h3>Inventory List</h3>
@endsection

@section('main-content')
    <div class="container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Condition</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventaris as $item)
                    <tr>
                        <td>{{ $item->id_inventaris }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->kondisi }}</td>
                        <td>
                            @if(auth()->user()->role == 'kalab')
                                <a href="{{ route('inventaris.edit', $item->id_inventaris) }}" class="btn btn-primary">Edit</a>
                                <form action="{{ route('inventaris.destroy', $item->id_inventaris) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            @elseif(auth()->user()->role == 'hmif')
                                <a href="{{ route('peminjaman.create') }}" class="btn btn-success">Request to Borrow</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection