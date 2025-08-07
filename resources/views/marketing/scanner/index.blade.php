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

    {{-- Tombol Pilih --}}
    <div class="m-3 d-flex gap-3">
        <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click();">
            <i class="fas fa-upload"></i> Upload Foto
        </button>
        <!-- <button type="button" class="btn btn-success" onclick="document.getElementById('cameraInput').click();">
            <i class="fas fa-camera"></i> Ambil dari Kamera
        </button> -->
    </div>

    {{-- Form --}}
    <form id="scanForm" action="{{ route('marketing.scanner.upload') }}" method="POST" enctype="multipart/form-data">
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
@endsection

@push('scripts')
<script>
    const previewContainer = document.getElementById('preview');
    const hiddenInputsContainer = document.getElementById('hiddenInputs');
    const scanForm = document.getElementById('scanForm');

    let selectedFiles = [];

    function addImages(files) {
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function (e) {
                const wrapper = document.createElement('div');
                wrapper.classList.add('position-relative');

                const img = document.createElement('img');
                img.src = e.target.result;
                img.style = 'width: 150px; height: auto; border: 1px solid #ccc; padding: 5px;';
                wrapper.appendChild(img);

                const closeBtn = document.createElement('button');
                closeBtn.innerHTML = '&times;';
                closeBtn.type = 'button';
                closeBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0';
                closeBtn.style = 'margin: 2px; border-radius: 50%; padding: 0 6px;';
                closeBtn.onclick = function () {
                    const index = selectedFiles.indexOf(file);
                    if (index !== -1) {
                        selectedFiles.splice(index, 1);
                    }
                    wrapper.remove();
                };

                wrapper.appendChild(closeBtn);
                previewContainer.appendChild(wrapper);

                selectedFiles.push(file);
            };

            reader.readAsDataURL(file);
        }
    }

    scanForm.addEventListener('submit', function (e) {
        if (selectedFiles.length === 0) {
            e.preventDefault();
            alert("Silakan pilih atau ambil gambar terlebih dahulu.");
            return;
        }

        hiddenInputsContainer.innerHTML = '';

        selectedFiles.forEach(file => {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);

            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.name = 'images[]';
            fileInput.files = dataTransfer.files;
            fileInput.classList.add('d-none');

            hiddenInputsContainer.appendChild(fileInput);
        });
    });

    document.getElementById('fileInput').addEventListener('change', function () {
        addImages(this.files);
        this.value = '';
    });

    document.getElementById('cameraInput').addEventListener('change', function () {
        addImages(this.files);
        this.value = '';
    });
</script>
@endpush
