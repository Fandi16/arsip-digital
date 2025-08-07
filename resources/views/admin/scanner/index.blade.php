@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">📄 Scan Dokumen</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Oops!</strong> Ada kesalahan:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tombol Pilih / Kamera --}}
    <div class="m-3 d-flex gap-3">
        <button type="button" class="btn btn-primary m-2" onclick="document.getElementById('fileInput').click();">
            <i class="fas fa-upload"></i> Upload Foto
        </button>
        <button type="button" class="btn btn-success m-2" onclick="openCamera()">
            <i class="fas fa-camera"></i> Ambil dari Kamera
        </button>
    </div>

    {{-- Kamera View --}}
    <div id="cameraSection" class="mt-4" style="display: none;">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h5 class="mb-3">📷 Kamera Aktif</h5>
                <div class="d-flex justify-content-center">
                    <video id="video" class="rounded shadow camera-video" autoplay playsinline ></video>
                </div>

                <div class="mt-3 d-flex justify-content-center gap-3 flex-wrap">
                    <button type="button" class="btn btn-warning" onclick="captureImage()">
                        <i class="fas fa-camera"></i> Ambil Gambar
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeCamera()">
                        <i class="fas fa-times"></i> Tutup Kamera
                    </button>
                </div>
            </div>
        </div>
    </div>

    <canvas id="canvas" style="display:none;"></canvas>

    {{-- Form --}}
    <form id="scanForm" action="{{ route('admin.scanner.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Hidden Inputs --}}
        <input type="file" id="fileInput" class="d-none" accept="image/*" multiple>
        <input type="file" id="cameraInput" class="d-none" accept="image/*" capture="environment">

        <div id="hiddenInputs"></div>

        {{-- Preview --}}
        <div id="preview" class="d-flex flex-wrap gap-3 mt-4"></div>

        <button type="submit" class="btn btn-danger mt-4">
            <i class="fas fa-file-pdf"></i> Buat PDF & Lanjut Isi Arsip
        </button>
    </form>
</div>

{{-- Modal Filter --}}
<div id="filterModal" 
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
            background:rgba(0,0,0,0.6); justify-content:center; align-items:center;">
    <div style="background:white; padding:20px; border-radius:10px; text-align:center; max-width:600px; margin:auto;">
        <h5>🖼 Edit Gambar</h5>
        <img id="modalImage" src="" style="max-width:100%; max-height:60vh;" class="mb-3">

        <div class="d-flex gap-2 justify-content-center mb-3 flex-wrap">
            <button class="btn btn-primary" onclick="applyFilter('scanner')">📄 Mode Scanner</button>
        </div>


        <div class="d-flex gap-2 justify-content-center">
            <button class="btn btn-success" onclick="saveFilter()">Simpan</button>
            <button class="btn btn-danger" onclick="closeFilterModal()">Batal</button>
        </div>
    </div>
</div>

<style>
    .camera-video {
        max-width: 100%;
        height: auto;
        object-fit: cover;
    }

    @media (max-width: 768px) {
        /* HP: portrait penuh */
        .camera-video {
            height: 70vh;
            object-fit: cover;
        }
    }

</style>

@endsection

@push('scripts')
<script src="{{ asset('js/scan-filters.js') }}"></script>
@endpush
