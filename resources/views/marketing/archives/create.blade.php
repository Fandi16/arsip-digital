@extends('layouts.marketing')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Tambah Arsip Baru</h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('marketing.archives.store') }}" method="POST" enctype="multipart/form-data">
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
                        <input type="text" name="wilayah" class="form-control" value="{{ auth()->user()->wilayah ? json_decode(auth()->user()->wilayah)[0] : '' }}" readonly required>
                    </div>

                    <div class="col-md-6">
                        <label>Plafond (Rp)</label>
                        <input type="number" name="plafond" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Kategori</label>
                        <select name="kategori" class="form-select" >
                            <option value="">-- Pilih Kategori --</option>
                            <option value="berkas">Berkas</option>
                            <option value="spk">SPK</option>
                            <option value="proposal">Proposal</option>
                            <option value="jaminan">Jaminan</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>File (PDF / DOC / DOCX / XLSX)</label>
                        <input type="file" name="file" class="form-control" >
                    </div>

                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('marketing.archives.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
