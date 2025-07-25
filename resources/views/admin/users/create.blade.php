@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Tambah User</h1>

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Form Tambah User</h3>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" id="role" class="form-control" onchange="toggleWilayah(this.value)" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin_marketing">Admin Marketing</option>
                        <option value="marketing">Marketing</option>
                    </select>
                </div>

                <div class="form-group d-none" id="wilayah-field">
                    <label>Wilayah (boleh pilih lebih dari satu)</label>
                    <select name="wilayah[]" class="form-control" multiple>
                        <option value="BERGAS">BERGAS</option>
                        <option value="BAWEN">BAWEN</option>
                    </select>
                    @error('wilayah')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleWilayah(role) {
        let wilayahGroup = document.getElementById('wilayah-field');
        if (role === 'admin_marketing' || role === 'marketing') {
            wilayahGroup.classList.remove('d-none');
        } else {
            wilayahGroup.classList.add('d-none');
        }
    }
</script>
@endsection
