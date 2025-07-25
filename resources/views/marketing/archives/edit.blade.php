@extends('layouts.marketing')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Edit Arsip</h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('marketing.archives.update', $archive->id) }}" method="POST" enctype="multipart/form-data">
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
                        <input type="text" name="wilayah" class="form-control" value="{{ $archive->wilayah }}" readonly required>
                    </div>

                    <div class="col-md-6">
                        <label>Plafond (Rp)</label>
                        <input type="number" name="plafond" class="form-control" value="{{ $archive->plafond }}" required>
                    </div>

                    <div class="col-md-6">
                        <label>Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="berkas" {{ $archive->kategori == 'berkas' ? 'selected' : '' }}>Berkas</option>
                            <option value="spk" {{ $archive->kategori == 'spk' ? 'selected' : '' }}>SPK</option>
                            <option value="proposal" {{ $archive->kategori == 'proposal' ? 'selected' : '' }}>Proposal</option>
                            <option value="jaminan" {{ $archive->kategori == 'jaminan' ? 'selected' : '' }}>Jaminan</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>File (PDF / DOC / DOCX / XLSX)</label>
                        <input type="file" name="file" class="form-control">
                        <small class="text-muted">Biarkan kosong jika tidak ingin ganti file.</small>
                    </div>

                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="{{ route('marketing.archives.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
