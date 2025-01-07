@extends('layouts.starter')

@section('header-content')
    <h3>{{ $room->nama_ruangan }}</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editFacilityModal = document.getElementById('editFacilityModal');

            editFacilityModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-nama');
                const kondisi = button.getAttribute('data-kondisi');

                const form = document.getElementById('editFacilityForm');
                form.action = `/stafflab/roomsFacilities/${id}`;
                document.getElementById('edit_nama_barang').value = nama;
                document.getElementById('edit_kondisi_barang').value = kondisi;
            });
        });
    </script>
@endsection


