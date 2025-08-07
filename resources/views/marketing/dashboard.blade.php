@extends('layouts.marketing')

@section('content')
    <h1>Dashboard Marketing</h1>
    <div class="col-md-6">
        <a href="{{ route('admin_marketing.scanner.index') }}" class="text-decoration-none">
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
@endsection