<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Archives;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArchivesController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;

        if ($role === 'admin_marketing') {
            $wilayah = json_decode($user->wilayah ?? '[]', true);
            $archives = Archives::whereIn('wilayah', $wilayah)->with('user')->get();
            return view('admin_marketing.archives.index', compact('archives'));

        } elseif ($role === 'marketing') {
            $archives = Archives::where('user_id', $user->id)->with('user')->get();
            return view('marketing.archives.index', compact('archives'));

        } else {
            $archives = Archives::with('user')->get();
            return view('admin.archives.index', compact('archives'));
        }
    }

    public function create()
    {
        $user = Auth::user();
        $wilayah = in_array($user->role, ['admin_marketing', 'marketing'])
            ? json_decode($user->wilayah ?? '[]', true)
            : ['BERGAS', 'BAWEN', 'UNGARAN'];

        $users = User::whereIn('role', ['marketing', 'admin_marketing'])
            ->where(function ($query) use ($wilayah) {
                foreach ($wilayah as $w) {
                    $query->orWhereJsonContains('wilayah', $w);
                }
            })
            ->get();

        $view = match ($user->role) {
            'admin_marketing' => 'admin_marketing.archives.create',
            'marketing'       => 'marketing.archives.create',
            default           => 'admin.archives.create',
        };

        return view($view, compact('users', 'wilayah'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'cif'               => 'required',
            'rekening_pinjaman' => 'required',
            'nama'              => 'required',
            'wilayah'           => 'required',
            'plafond'           => 'required|numeric',
            'kategori'          => 'required|in:berkas,spk,proposal,jaminan',
            'file'              => 'required|mimes:pdf,doc,docx,xlsx|max:512000',
        ];

        if ($user->role === 'admin') {
            $rules['user_id'] = 'required|exists:users,id';
        }

        $request->validate($rules);
        $userId = $user->role === 'admin' ? $request->user_id : $user->id;

        $arsipName = Str::slug($request->nama);
        $ext = $request->file('file')->getClientOriginalExtension();
        $fileName = $arsipName . '-' . time() . '.' . $ext;
        $request->file('file')->storeAs('public/arsip', $fileName);

        Archives::create([
            'cif'               => $request->cif,
            'rekening_pinjaman' => $request->rekening_pinjaman,
            'nama'              => $request->nama,
            'wilayah'           => $request->wilayah,
            'user_id'           => $userId,
            'plafond'           => $request->plafond,
            'kategori'          => $request->kategori,
            'file'              => $fileName,
        ]);

        return $this->redirectAfterAction('Data arsip berhasil ditambahkan.');
    }

    public function edit(Archives $archive)
    {
        $user = Auth::user();
        $wilayah = json_decode($user->wilayah ?? '[]', true);

        $users = User::whereIn('role', ['marketing', 'admin_marketing'])
            ->where(function ($query) use ($wilayah) {
                foreach ($wilayah as $w) {
                    $query->orWhereJsonContains('wilayah', $w);
                }
            })
            ->get();

        $view = match ($user->role) {
            'admin_marketing' => 'admin_marketing.archives.edit',
            'marketing'       => 'marketing.archives.edit',
            default           => 'admin.archives.edit',
        };

        return view($view, compact('archive', 'users', 'wilayah'));
    }

    public function update(Request $request, Archives $archive)
    {
        $rules = [
            'cif'               => 'required',
            'rekening_pinjaman' => 'required',
            'nama'              => 'required',
            'wilayah'           => 'required',
            'plafond'           => 'required|numeric',
            'kategori'          => 'required|in:berkas,spk,proposal,jaminan',
            'file'              => 'nullable|mimes:pdf,doc,docx,xlsx|max:512000',
        ];

        if (auth()->user()->role === 'admin') {
            $rules['user_id'] = 'required|exists:users,id';
        }

        $request->validate($rules);

        if ($request->hasFile('file')) {
            Storage::delete('public/arsip/' . $archive->file);
            $arsipName = Str::slug($request->nama);
            $ext = $request->file('file')->getClientOriginalExtension();
            $fileName = $arsipName . '-' . time() . '.' . $ext;
            $request->file('file')->storeAs('public/arsip', $fileName);
            $archive->file = $fileName;
        }

        $archive->update([
            'cif'               => $request->cif,
            'rekening_pinjaman' => $request->rekening_pinjaman,
            'nama'              => $request->nama,
            'wilayah'           => $request->wilayah,
            'user_id'           => auth()->user()->role === 'admin' ? $request->user_id : $archive->user_id,
            'plafond'           => $request->plafond,
            'kategori'          => $request->kategori,
            'file'              => $archive->file,
        ]);

        return $this->redirectAfterAction('Data arsip berhasil diperbarui.');
    }

    public function destroy(Archives $archive)
    {
        Storage::delete('public/arsip/' . $archive->file);
        $archive->delete();

        return $this->redirectAfterAction('Data arsip berhasil dihapus.');
    }

    private function redirectAfterAction($message)
    {
        $role = Auth::user()->role;

        return match ($role) {
            'admin_marketing' => redirect()->route('admin_marketing.archives.index')->with('success', $message),
            'marketing'       => redirect()->route('marketing.archives.index')->with('success', $message),
            default           => redirect()->route('admin.archives.index')->with('success', $message),
        };
    }
}
