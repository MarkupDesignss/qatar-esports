@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

    <h4 class="mb-4 fw-bold">Edit Logo</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.logo.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Title</label>
                    <input type="text" name="title" class="form-control"
                        value="{{ old('title', $logo->title) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Current Logo</label><br>
                    <img src="{{ asset('storage/' . $logo->image) }}"
                         style="height:80px;border:1px solid #ddd;padding:5px">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Change Logo</label>
                    <input type="file" name="image" class="form-control">
                    <small class="text-muted">PNG, JPG, SVG allowed</small>
                </div>

                <button class="btn btn-primary">Update Logo</button>
            </form>
        </div>
    </div>

</div>
@endsection
