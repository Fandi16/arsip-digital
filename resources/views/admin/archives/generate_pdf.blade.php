@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Generate PDF dari Gambar (Crop & Edit)</h1>

   <form id="form-generate" action="{{ route('admin.archives.storeGenerated') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="cropped_images" id="cropped_images">

    <div class="form-group">
        <label>Nama Arsip</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
    <div class="form-group">
        <label>CIF</label>
        <input type="text" name="cif" class="form-control" required>
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
        <label>Pilih Marketing</label>
        <select name="user_id" class="form-control" required>
            @foreach (App\Models\User::whereIn('role', ['admin_marketing', 'marketing'])->get() as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Upload Foto (bisa berkali-kali klik)</label>
        <input type="file" id="inputImages" accept="image/*" class="form-control">
    </div>

    <div id="preview-container" style="display:flex; flex-wrap:wrap; gap:10px;"></div>

    <button type="submit" class="btn btn-primary">Generate & Simpan PDF</button>
</form>

    <style>
        #preview-container img,
        #cropped-preview img {
            max-width: 200px;
            margin: 5px;
            border: 1px solid #ddd;
            padding: 5px;
        }
    </style>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
    let croppedImages = [];
    const previewContainer = document.getElementById('preview-container');
    const croppedPreview = document.getElementById('cropped-preview');

    document.getElementById('inputImages').addEventListener('change', function(e){
        const existingCount = croppedImages.length;

        Array.from(e.target.files).forEach((file, index) => {
            let reader = new FileReader();
            reader.onload = function(evt) {
                let image = document.createElement('img');
                image.src = evt.target.result;
                image.id = 'img-' + (existingCount + index);
                image.style.maxWidth = '200px';
                previewContainer.appendChild(image);

                let cropper = new Cropper(image, {
                    aspectRatio: NaN,
                    viewMode: 1,
                    cropend() {
                        const canvas = cropper.getCroppedCanvas();
                        const base64 = canvas.toDataURL('image/jpeg');
                        croppedImages[existingCount + index] = base64;
                        refreshCroppedPreview();
                    },
                    ready() {
                        const canvas = cropper.getCroppedCanvas();
                        const base64 = canvas.toDataURL('image/jpeg');
                        croppedImages[existingCount + index] = base64;
                        refreshCroppedPreview();
                    }
                });
            };
            reader.readAsDataURL(file);
        });
    });

    function refreshCroppedPreview() {
        croppedPreview.innerHTML = '';
        croppedImages.forEach(base64 => {
            if (base64) {
                const img = document.createElement('img');
                img.src = base64;
                croppedPreview.appendChild(img);
            }
        });
    }

    document.getElementById('form-generate').addEventListener('submit', function(e){
        e.preventDefault();
        if (croppedImages.length === 0 || croppedImages.filter(img => img).length === 0) {
            alert('Silahkan upload dan crop gambar terlebih dahulu.');
            return;
        }
        document.getElementById('cropped_images').value = JSON.stringify(croppedImages);
        this.submit();
    });
</script>
@endsection
