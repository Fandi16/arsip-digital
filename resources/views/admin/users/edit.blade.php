@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Edit User</h1>

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Form Edit User</h3>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" id="role" class="form-control" onchange="toggleWilayah(this.value)" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin_marketing" {{ $user->role == 'admin_marketing' ? 'selected' : '' }}>Admin Marketing</option>
                        <option value="marketing" {{ $user->role == 'marketing' ? 'selected' : '' }}>Marketing</option>
                    </select>
                </div>

                <div class="form-group d-none" id="wilayah-field">
                    <label>Wilayah</label>
                    <select name="wilayah[]" class="form-control" multiple>
                        <option value="BERGAS">BERGAS</option>
                        <option value="BAWEN">BAWEN</option>
                    </select>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('role').addEventListener('change', function () {
        let wilayahGroup = document.getElementById('wilayah-field');
        if (this.value === 'admin_marketing' || this.value === 'marketing') {
            wilayahGroup.classList.remove('d-none');
        } else {
            wilayahGroup.classList.add('d-none');
        }
    });
</script>
@endsection
