const previewContainer = document.getElementById('preview');
const hiddenInputsContainer = document.getElementById('hiddenInputs');
const scanForm = document.getElementById('scanForm');
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const cameraSection = document.getElementById('cameraSection');

let mediaStream = null;
let selectedFiles = [];
let currentImg = null;
let currentFile = null;

function openCamera() {
    navigator.mediaDevices.getUserMedia({ 
        video: { width: { ideal: 1920 }, height: { ideal: 1080 } } 
    })
    .then(stream => {
        mediaStream = stream;
        video.srcObject = stream;
        cameraSection.style.display = 'block';
    })
    .catch(err => {
        alert('Tidak bisa mengakses kamera: ' + err.message);
    });
}

function closeCamera() {
    if (mediaStream) {
        mediaStream.getTracks().forEach(track => track.stop());
        video.srcObject = null;
    }
    cameraSection.style.display = 'none';
}

function captureImage() {
    const vw = video.videoWidth;
    const vh = video.videoHeight;

    // Rasio target A4 portrait 3:4
    const targetRatio = 3 / 4;
    let cropWidth = vw;
    let cropHeight = Math.floor(vw / targetRatio);

    if (cropHeight > vh) {
        cropHeight = vh;
        cropWidth = Math.floor(vh * targetRatio);
    }

    // Crop tengah agar sesuai A4
    const startX = Math.floor((vw - cropWidth) / 2);
    const startY = Math.floor((vh - cropHeight) / 2);

    canvas.width = cropWidth;
    canvas.height = cropHeight;
    const context = canvas.getContext('2d');
    context.drawImage(video, startX, startY, cropWidth, cropHeight, 0, 0, cropWidth, cropHeight);

    canvas.toBlob(function (blob) {
        const file = new File([blob], `camera_${Date.now()}.jpg`, { type: 'image/jpeg' });
        addImages([file]);
    }, 'image/jpeg', 0.95);
}


function addImages(files) {
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();

        reader.onload = function (e) {
            const wrapper = document.createElement('div');
            wrapper.classList.add('position-relative', 'm-3');

            const img = document.createElement('img');
            img.src = e.target.result;
            img.style = 'width: 150px; height: auto; border: 1px solid #ccc; padding: 5px; cursor:pointer;';
            img.onclick = () => openFilterModal(img, file);
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

// ==== FILTER MODAL ====

function openFilterModal(imgElement, file) {
    currentImg = imgElement;
    currentFile = file;
    document.getElementById("modalImage").src = imgElement.src;
    document.getElementById("filterModal").style.display = "flex";
}


function applyFilter(type) {
    const modalImg = document.getElementById("modalImage");
    const canvas = document.createElement("canvas");
    const ctx = canvas.getContext("2d");

    const img = new Image();
    img.src = modalImg.src;
    img.onload = () => {
        canvas.width = img.width;
        canvas.height = img.height;
        ctx.drawImage(img, 0, 0);

        let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        let data = imageData.data;

        if (type === "scanner") {
            // Efek mirip CamScanner
            for (let i = 0; i < data.length; i += 4) {
                // Ambil RGB
                let r = data[i];
                let g = data[i + 1];
                let b = data[i + 2];

                // Konversi ke brightness rata-rata
                let brightness = (r + g + b) / 3;

                // Tingkatkan kontras
                brightness = brightness > 128 
                    ? Math.min(brightness + 40, 255) 
                    : Math.max(brightness - 40, 0);

                // Putihkan background & pertajam teks
                if (brightness > 200) {
                    brightness = 255; // Putih bersih
                }

                // Simpan ke RGB
                data[i] = data[i + 1] = data[i + 2] = brightness;
            }
        }

        ctx.putImageData(imageData, 0, 0);
        modalImg.src = canvas.toDataURL("image/jpeg", 0.9);
    };
}


function saveFilter() {
    if (!currentImg) return;

    const modalImg = document.getElementById("modalImage");
    currentImg.src = modalImg.src;

    // Update File object agar PDF pakai gambar terbaru
    fetch(modalImg.src)
        .then(res => res.blob())
        .then(blob => {
            const newFile = new File([blob], currentFile.name, { type: 'image/jpeg' });
            const index = selectedFiles.indexOf(currentFile);
            if (index !== -1) {
                selectedFiles[index] = newFile;
            }
            currentFile = newFile;
            closeFilterModal();
        });
}

function closeFilterModal() {
    document.getElementById("filterModal").style.display = "none";
}
