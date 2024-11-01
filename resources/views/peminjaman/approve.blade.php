@extends('layouts.starter')

@section('header-content')
    <h3>Approve Request</h3>
@endsection

@section('main-content')
    <div class="container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Item</th>
                    <th>Requested By</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $request)
                    <tr>
                        <td>{{ $request->id_peminjaman_inventaris }}</td>
                        <td>{{ $request->inventaris->nama_barang }}</td>
                        <td>{{ $request->user->name }}</td>
                        <td>{{ $request->keterangan_peminjaman }}</td>
                        <td>{{ $request->status }}</td>
                        <td>
                            @if($request->status == 'pending')
                                <form action="{{ route('peminjaman.update', $request->id_peminjaman_inventaris) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-success">Approve</button>
                                </form>
                                <form action="{{ route('peminjaman.update', $request->id_peminjaman_inventaris) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-danger">Reject</button>
                                </form>
                            @else
                                {{ ucfirst($request->status) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection