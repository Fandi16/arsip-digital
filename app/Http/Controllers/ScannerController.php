<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Archives;
use Illuminate\Support\Facades\Auth;

class ScannerController extends Controller
{
    // 1. Halaman Scanner (upload atau kamera)
    public function index()
    {
        return view('admin.scanner.index');
    }

    // 2. Upload gambar → convert PDF → lanjut ke form arsip
    public function upload(Request $request)
    {
        $request->validate([
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $paths = [];

        foreach ($request->file('images') as $image) {
            $filename = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/temp-images', $filename);
            $paths[] = storage_path('app/' . $path);
        }

        // Buat PDF dari gambar
        $pdf = Pdf::loadView('admin.scanner.generated_pdf', ['images' => $paths])->setPaper('a4');
        $fileName = 'scan-' . time() . '.pdf';
        Storage::put('public/arsip/' . $fileName, $pdf->output());

        return view('admin.scanner.form', [
            'pdf' => $fileName,
        ]);
    }

    // 3. Simpan ke arsip database
   public function store(Request $request)
    {
        $request->validate([
            'nama'               => 'required|string|max:255',
            'cif'                => 'required|string|max:100',
            'rekening_pinjaman' => 'required|string|max:100',
            'wilayah'           => 'required|string|max:100',
            'ao'                => 'required|string|max:100',
            'plafond'           => 'required|numeric',
            'kategori'          => 'required|string|max:100',
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

        return redirect()->route('admin.archives.index')->with('success', 'Hasil scan berhasil disimpan.');
    }

    public function downloadPdf(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $paths = [];

        $images = $request->file('images') ?? [];

        foreach ($images as $image) {
            $filename = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/temp-images', $filename);
            $paths[] = storage_path('app/' . $path);
        }

        $pdf = Pdf::loadView('admin.scanner.generated_pdf', ['images' => $paths])->setPaper('a4');

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="scan_' . time() . '.pdf"',
        ]);
    }

}
