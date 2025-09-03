@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Tambah Data Arsip</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.archives.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>CIF</label>
                    <input type="text" name="cif" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Rekening Pinjaman</label>
                    <input type="text" name="rekening_pinjaman" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Nama Debitur</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Wilayah</label>
                    <select name="wilayah" class="form-control" required>
                        <option value="BERGAS">BERGAS</option>
                        <option value="BAWEN">BAWEN</option>
                        <option value="UNGARAN">UNGARAN</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Pilih Marketing / AO</label>
                    <select name="user_id" class="form-control" required>
                        @foreach (App\Models\User::whereIn('role', ['admin_marketing', 'marketing'])->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Plafond</label>
                    <input type="number" name="plafond" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" class="form-control">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="berkas" {{ old('kategori') == 'berkas' ? 'selected' : '' }}>Berkas</option>
                        <option value="spk" {{ old('kategori') == 'spk' ? 'selected' : '' }}>SPK</option>
                        <option value="proposal" {{ old('kategori') == 'proposal' ? 'selected' : '' }}>Proposal</option>
                        <option value="jaminan" {{ old('kategori') == 'jaminan' ? 'selected' : '' }}>Jaminan</option>
                    </select>
                </div>


                <div class="form-group">
                    <label>Upload File</label>
                    <input type="file" name="file" class="form-control" >
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.archives.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
