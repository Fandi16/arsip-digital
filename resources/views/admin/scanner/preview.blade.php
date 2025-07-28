@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h4>📄 Preview Hasil Scan</h4>

    <div class="card">
        <div class="card-body">
            <iframe src="{{ $url }}" width="100%" height="700px" style="border: 1px solid #ccc;"></iframe>
        </div>
    </div>

    <a href="{{ $url }}" class="btn btn-success mt-3" download>
        <i class="fas fa-download"></i> Download PDF
    </a>
</div>
@endsection
