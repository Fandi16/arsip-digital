@extends('layouts.admin')

@section('content')
<h1 class="mb-4">Dashboard Admin</h1>

<div class="row">
    <div class="col-md-6">
        <div class="card bg-primary text-white shadow">
            <div class="card-body">
                <h5 class="card-title pr-3">Total User</h5>
                <h2>{{ $userCount }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-success text-white shadow">
            <div class="card-body">
                <h5 class="card-title pr-3">Total Arsip</h5>
                <h2>{{ $archiveCount }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <a href="{{ route('admin.scanner.index') }}" class="text-decoration-none">
            <div class="card bg-warning text-white shadow">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Scan Dokumen</h5>
                        <p class="mb-0">Klik untuk memindai dokumen dan ubah ke PDF</p>
                    </div>
                    <i class="fas fa-camera fa-2x"></i>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
