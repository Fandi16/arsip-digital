@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h4>Lengkapi Data Arsip dari Hasil Scan</h4>

    {{-- Tampilkan pesan error jika ada --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form method="POST" action="{{ route('marketing.scanner.store') }}">
         @if ($pdf)
            <div class="mt-5">
                <h5>Preview PDF Hasil Scan</h5>
                <embed src="{{ asset('storage/arsip/' . $pdf) }}" type="application/pdf" width="100%" height="600px" />
                <a href="{{ asset('storage/arsip/' . $pdf) }}" class="btn btn-success mt-2" download>Download PDF</a>
            </div>
        @endif

        @csrf
        <input type="hidden" name="pdf" value="{{ $pdf }}">

        <div class="mb-3">
            <label for="nama">Nama Nasabah</label>
            <input type="text" id="nama" name="nama" class="form-control" value="{{ old('nama') }}" required>
        </div>

        <div class="mb-3">
            <label for="cif">CIF</label>
            <input type="text" id="cif" name="cif" class="form-control" value="{{ old('cif') }}" required>
        </div>

        <div class="mb-3">
            <label for="rekening_pinjaman">Rekening Pinjaman</label>
            <input type="text" id="rekening_pinjaman" name="rekening_pinjaman" class="form-control" value="{{ old('rekening_pinjaman') }}" required>
        </div>
        <div class="mb-3">
            <label for="wilayah">Wilayah</label>
            <select id="wilayah" name="wilayah" class="form-control" required>
                <option value="">-- Pilih Wilayah --</option>
                @foreach (['BERGAS', 'BAWEN'] as $area)
                    <option value="{{ $area }}" {{ old('wilayah') == $area ? 'selected' : '' }}>
                        {{ $area }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="user_id">Upload Oleh (Pilih AO)</label>
            <select name="user_id" id="user_id" class="form-control" required>
                <option value="">-- Pilih AO --</option>
                @foreach(\App\Models\User::where('role', 'marketing')->get() as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="plafond">Plafond</label>
            <input type="number" id="plafond" name="plafond" class="form-control" value="{{ old('plafond') }}" required>
        </div>

        <div class="mb-3">
            <label for="kategori">Kategori</label>
            <select id="kategori" name="kategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="berkas" {{ old('kategori') == 'berkas' ? 'selected' : '' }}>Berkas</option>
                <option value="spk" {{ old('kategori') == 'spk' ? 'selected' : '' }}>SPK</option>
                <option value="proposal" {{ old('kategori') == 'proposal' ? 'selected' : '' }}>Proposal</option>
                <option value="jaminan" {{ old('kategori') == 'jaminan' ? 'selected' : '' }}>Jaminan</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan ke Arsip</button>
    </form>
</div>
@endsection
