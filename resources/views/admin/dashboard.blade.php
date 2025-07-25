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
</div>
@endsection
