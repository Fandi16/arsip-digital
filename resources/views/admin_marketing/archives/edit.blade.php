@extends('layouts.admin_marketing')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Edit Arsip</h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin_marketing.archives.update', $archive->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>CIF</label>
                        <input type="text" name="cif" class="form-control" value="{{ $archive->cif }}" required>
                    </div>
                    <div class="col-md-6">
                        <label>Rekening Pinjaman</label>
                        <input type="text" name="rekening_pinjaman" class="form-control" value="{{ $archive->rekening_pinjaman }}" required>
                    </div>
                    <div class="col-md-6">
                        <label>Nama Nasabah</label>
                        <input type="text" name="nama" class="form-control" value="{{ $archive->nama }}" required>
                    </div>
                    <div class="col-md-6">
                        <label>Wilayah</label>
                        <input type="text" name="wilayah" value="{{ $archive->wilayah }}" class="form-control" required readonly>
                    </div>
                    <div class="col-md-6">
                        <label>Plafond</label>
                        <input type="number" name="plafond" class="form-control" value="{{ $archive->plafond }}" required>
                    </div>
                    <div class="col-md-6">
                        <label>Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="berkas" {{ $archive->kategori == 'berkas' ? 'selected' : '' }}>Berkas</option>
                            <option value="spk" {{ $archive->kategori == 'spk' ? 'selected' : '' }}>SPK</option>
                            <option value="proposal" {{ $archive->kategori == 'proposal' ? 'selected' : '' }}>Proposal</option>
                            <option value="jaminan" {{ $archive->kategori == 'jaminan' ? 'selected' : '' }}>Jaminan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>File Arsip (Opsional)</label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xlsx">
                        <small class="text-muted">Abaikan jika tidak ingin mengganti file.</small>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning mt-4">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
