@extends('layouts.starter')

@section('header-content')
@endsection

@section('main-content')
    <div class="container mt-4">
        <h1>Halo Anggota HMIF, {{ Auth::user()->name }}</h1>

        <!-- Tombol Navigasi -->
        <!-- <a href="{{ url('hmif/pengajuanRuangan') }}" class="btn btn-primary mr-2">Ajukan Peminjaman Ruangan</a>
        <a href="{{ url('hmif/statusPemRuangan') }}" class="btn btn-primary mr-2">Cek Status Peminjaman Ruangan</a>
        <a href="{{ url('hmif/jadwalRuangan') }}" class="btn btn-primary mr-2">Lihat Jadwal Ruangan</a> -->

        <!-- Peraturan Peminjaman Ruangan -->
        <div class="mt-5">
            <h2>Peraturan Peminjaman Ruangan</h2>
            <ol>
                <li>Peminjaman ruangan hanya diperbolehkan untuk kegiatan dengan tujuan akademik.</li>
                <li>Pengajuan peminjaman ruangan harus dilakukan minimal <strong>3 hari</strong> sebelum hari kegiatan.</li>
                <li>Pengguna wajib menyertakan <strong>surat peminjaman</strong> yang valid saat mengajukan peminjaman.</li>
                <li>Ruangan harus dikembalikan dalam kondisi bersih dan rapi.</li>
                <li>Kerusakan atau kehilangan barang selama penggunaan ruangan menjadi tanggung jawab peminjam.</li>
                <li>Peminjam harus mematuhi waktu yang sudah diajukan. Keterlambatan dapat dikenakan sanksi.</li>
                <li>Pengajuan dapat ditolak jika ruangan sudah terjadwal atau tidak memenuhi persyaratan.</li>
            </ol>

            <h2>Peraturan Peminjaman Barang</h2>
            <ol>
                <li>Peminjaman barang hanya diperbolehkan untuk kegiatan dengan tujuan akademik.</li>
                <li>Pengajuan peminjaman barang harus dilakukan minimal <strong>3 hari</strong> sebelum tanggal penggunaan.</li>
                <li>Barang yang dipinjam harus dikembalikan dalam kondisi baik dan sesuai dengan waktu yang telah disepakati.</li>
                <li>Kerusakan atau kehilangan barang selama masa pinjam menjadi tanggung jawab peminjam, dan peminjam wajib mengganti barang tersebut.</li>
                <li>Surat peminjaman atau dokumen pendukung harus dilampirkan saat pengajuan peminjaman barang.</li>
                <li>Barang yang sudah terjadwal untuk dipinjam oleh anggota lain tidak dapat dipinjam di waktu yang sama.</li>
                <li>Kepala laboratorium berhak menolak pengajuan peminjaman barang jika tidak sesuai dengan prosedur atau kebutuhan.</li>
            </ol>
            <p class="mt-3">
                Mohon untuk mematuhi peraturan peminjaman ruangan dan barang demi menjaga inventaris program studi dan fakultas agar tetap terpelihara dengan baik.
                
            </p>
        </div>
    </div>
@endsection
