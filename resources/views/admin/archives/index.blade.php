@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Data Arsip</h4>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.archives.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Arsip
        </a>
        <input type="text" id="searchInput" class="form-control w-25" placeholder="Cari CIF / Nama / Wilayah / Marketing...">
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle text-center" id="archivesTable">
                    <thead class="table-dark">
                        <tr>
                            <th>NO</th>
                            <th>CIF</th>
                            <th>Rekening Pinjaman</th>
                            <th>Nama Nasabah</th>
                            <th>Wilayah</th>
                            <th>AO / Marketing</th>
                            <th>Plafond</th>
                            <th>Kategori</th>
                            <th>File</th>
                            <th>Tgl Input</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($archives as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->cif }}</td>
                                <td>{{ $item->rekening_pinjaman }}</td>
                                <td>{{ $item->nama }}</td>
                                <td><span class="badge bg-info">{{ $item->wilayah }}</span></td>
                                <td>{{ $item->user->name ?? '-' }}</td>
                                <td class="text-end">Rp {{ number_format($item->plafond, 0, ',', '.') }}</td>
                                <td><span class="badge bg-secondary">{{ $item->kategori }}</span></td>
                                <td>
                                    <a href="{{ asset('storage/arsip/' . $item->file) }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-file"></i> File
                                    </a>
                                </td>
                                <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.archives.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.archives.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted">Belum ada data arsip.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#archivesTable tbody tr');
        rows.forEach(row => {
            let rowText = row.innerText.toLowerCase();
            row.hidden = !rowText.includes(filter);
        });
    });
</script>
@endpush

