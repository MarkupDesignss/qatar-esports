@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <h4 class="fw-bold mb-3">Add Banner</h4>

    {{-- ERROR DISPLAY --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.banners.store') }}"
          enctype="multipart/form-data">
        @csrf

        {{-- HEADING --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Heading</label>
            <input type="text"
                   name="heading"
                   class="form-control @error('heading') is-invalid @enderror"
                   value="{{ old('heading') }}"
                   placeholder="Enter banner heading">

            @error('heading')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- DESCRIPTION --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description"
                      rows="3"
                      class="form-control @error('description') is-invalid @enderror"
                      placeholder="Enter banner description">{{ old('description') }}</textarea>

            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- IMAGE --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Banner Image</label>
            <input type="file"
                   name="image"
                   class="form-control @error('image') is-invalid @enderror">

            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            <small class="text-muted">Allowed: JPG, PNG, WEBP (Max 2MB)</small>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                Create Banner
            </button>

            <a href="{{ route('admin.banners.index') }}"
               class="btn btn-secondary ms-2">
                Cancel
            </a>
        </div>

    </form>

</div>
@endsection
