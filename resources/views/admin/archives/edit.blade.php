@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Edit Arsip</h1>

    <form action="{{ route('admin.archives.update', $archive->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Nama Arsip</label>
            <input type="text" name="nama" class="form-control" value="{{ $archive->nama }}" required>
        </div>
        <div class="form-group">
            <label>CIF</label>
            <input type="text" name="cif" class="form-control" value="{{ $archive->cif }}" required>
        </div>
        <div class="form-group">
            <label>Wilayah</label>
            <input type="text" name="wilayah" class="form-control" value="{{ $archive->wilayah }}" required>
        </div>
        <div class="form-group">
            <label>Nama Marketing / Admin Marketing</label>
            <select name="user_id" class="form-control" required>
                <option value="">-- Pilih --</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $user->id == $archive->user_id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->wilayah }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>File (Biarkan kosong jika tidak diubah)</label>
            <input type="file" name="file" class="form-control">
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.archives.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
