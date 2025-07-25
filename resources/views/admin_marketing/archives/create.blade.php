@extends('layouts.admin_marketing')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Tambah Arsip Baru</h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin_marketing.archives.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>CIF</label>
                        <input type="text" name="cif" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Rekening Pinjaman</label>
                        <input type="text" name="rekening_pinjaman" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Nama Nasabah</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Wilayah</label>
                        <input type="text" name="wilayah" value="{{ auth()->user()->wilayah ? json_decode(auth()->user()->wilayah)[0] : '' }}" class="form-control" required readonly>
                    </div>
                    <div class="col-md-6">
                        <label>Plafond</label>
                        <input type="number" name="plafond" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="berkas">Berkas</option>
                            <option value="spk">SPK</option>
                            <option value="proposal">Proposal</option>
                            <option value="jaminan">Jaminan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>File Arsip</label>
                        <input type="file" name="file" class="form-control" required accept=".pdf,.doc,.docx,.xlsx">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-4">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection
