<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Archives;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;


class ScannerController extends Controller
{
    // 1. Halaman awal scanner
    public function index()
    {
        $role = Auth::user()->role;

        if ($role === 'admin') {
            return view('admin.scanner.index');
        } elseif ($role === 'admin_marketing') {
            return view('admin_marketing.scanner.index');
        } elseif ($role === 'marketing') {
            return view('marketing.scanner.index');
        } else {
            abort(403);
        }
    }

// 2. Upload gambar → Convert PDF → Tampilkan form metadata
public function upload(Request $request)
{
    $request->validate([
        'images' => 'required|array',
        'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
    ]);

    $paths = [];

    foreach ($request->file('images') as $image) {
        // Baca gambar
        $img = \Intervention\Image\Facades\Image::make($image);

        // Tentukan ukuran A4 (portrait) dengan resolusi sedang
        $width = 1240;   // ~150 DPI
        $height = 1754;  // A4 portrait ratio

        // Resize gambar agar mengisi penuh A4 tanpa white space
        $img->fit($width, $height, function ($constraint) {
            $constraint->upsize(); // mencegah gambar jadi pecah
        });

        // Simpan sementara untuk PDF
        $filename = 'scan_' . uniqid() . '.jpg';
        $path = storage_path('app/public/temp-images/' . $filename);
        $img->save($path, 90); // simpan kualitas 90%

        $paths[] = $path;
    }

    // Generate PDF dari gambar
    $pdf = Pdf::loadView('components.generated_pdf', ['images' => $paths]);
    // Jangan pakai setPaper, biar otomatis
    $fileName = 'scan-' . time() . '.pdf';
    Storage::put('public/arsip/' . $fileName, $pdf->output());


    // Redirect sesuai role
    $role = \Auth::user()->role;
    if ($role === 'admin') {
        return view('admin.scanner.form', ['pdf' => $fileName]);
    } elseif ($role === 'admin_marketing') {
        return view('admin_marketing.scanner.form', ['pdf' => $fileName]);
    } elseif ($role === 'marketing') {
        return view('marketing.scanner.form', ['pdf' => $fileName]);
    } else {
        abort(403);
    }
}


    // 3. Simpan metadata arsip + nama file PDF ke DB
    public function store(Request $request)
    {
        $request->validate([
            'nama'               => 'required|string|max:255',
            'cif'                => 'required|string|max:100',
            'rekening_pinjaman' => 'required|string|max:100',
            'wilayah'           => 'required|string|max:100',
            'pdf'               => 'required|string',
            'user_id'           => 'required|exists:users,id',
        ]);

        Archives::create([
            'nama'               => $request->nama,
            'cif'                => $request->cif,
            'rekening_pinjaman' => $request->rekening_pinjaman,
            'wilayah'           => $request->wilayah,
            'ao'                => $request->ao,
            'plafond'           => $request->plafond,
            'kategori'          => $request->kategori,
            'file'              => $request->pdf,
            'tanggal_input'     => now(),
            'user_id'           => $request->user_id,
        ]);

        // Redirect berdasarkan role
        $role = Auth::user()->role;

        if ($role === 'admin') {
            return redirect()->route('admin.archives.index')->with('success', 'Hasil scan berhasil disimpan.');
        } elseif ($role === 'admin_marketing') {
            return redirect()->route('admin_marketing.archives.index')->with('success', 'Hasil scan berhasil disimpan.');
        } elseif ($role === 'marketing') {
            return redirect()->route('marketing.archives.index')->with('success', 'Hasil scan berhasil disimpan.');
        } else {
            abort(403);
        }
    }

    // 4. (Opsional) Generate dan Download PDF tanpa menyimpannya
    public function downloadPdf(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $paths = [];

        foreach ($request->file('images') as $image) {
            $filename = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/temp-images', $filename);
            $paths[] = storage_path('app/' . $path);
        }

        $pdf = Pdf::loadView('components.generated_pdf', ['images' => $paths])->setPaper('a4');

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="scan_' . time() . '.pdf"',
        ]);
    }
    public function enhance(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $file = $request->file('image');
        $image = Image::make($file)
            ->greyscale()
            ->contrast(25)
            ->brightness(10)
            ->sharpen(10);

        $fileName = 'enhanced_' . time() . '.jpg';
        $path = 'public/enhanced/' . $fileName;
        Storage::put($path, (string) $image->encode('jpg', 90));

        return response()->json([
            'filtered_image_url' => Storage::url($path),
        ]);
    }

}
