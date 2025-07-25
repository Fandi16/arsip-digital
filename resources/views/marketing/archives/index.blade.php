@extends('layouts.marketing')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Data Arsip Saya</h4>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold">Arsip Saya</span>
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari CIF / Nama / Wilayah..." style="width: 250px;">
            <a href="{{ route('marketing.archives.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Tambah Arsip
            </a>
        </div>

        
        <div class="card-body table-responsive">
            <table class="table table-hover table-bordered align-middle" id="archivesTable">
                <thead class="table-dark">
                    <tr class="text-center">
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
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $item->cif }}</td>
                            <td class="text-center">{{ $item->rekening_pinjaman }}</td>
                            <td>{{ $item->nama }}</td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $item->wilayah }}</span>
                            </td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                            <td class="text-end">Rp {{ number_format($item->plafond, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <span class="badge bg-secondary text-capitalize">{{ $item->kategori }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ asset('storage/arsip/' . $item->file) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file"></i> File
                                </a>
                            </td>
                            <td class="text-center">{{ $item->created_at->format('d-m-Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('marketing.archives.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('marketing.archives.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
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
