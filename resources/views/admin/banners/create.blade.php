@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    {{-- ERROR DISPLAY --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2 py-sm-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Add Banner
                </h4>
                <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary btn-sm w-sm-auto">
                    <i class="bi bi-arrow-left me-1"></i> Back to Banners
                </a>
            </div>
        </div>

        <div class="card-body p-3 p-sm-4">
            <form method="POST"
                  action="{{ route('admin.banners.store') }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-12 col-lg-8">

                        {{-- HEADING --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Heading <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="heading"
                                   class="form-control form-control-lg @error('heading') is-invalid @enderror"
                                   value="{{ old('heading') }}"
                                   placeholder="Enter banner heading"
                                   required>

                            @error('heading')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- DESCRIPTION --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description"
                                      rows="4"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Enter banner description">{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Brief description of the banner (optional)</small>
                        </div>

                        {{-- IMAGE / VIDEO --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Banner Media <span class="text-danger">*</span></label>
                            <input type="file"
                                   name="image"
                                   class="form-control @error('image') is-invalid @enderror"
                                   accept=".jpg,.jpeg,.png,.webp,.gif,.mp4,.webm,.ogg,.mov,.avi"
                                   required>

                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="mt-2">
                                <small class="text-muted d-block">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <strong>Supported formats:</strong>
                                </small>
                                <small class="text-muted d-block">
                                    Images: JPG, JPEG, PNG, WEBP, GIF
                                </small>
                                <small class="text-muted d-block">
                                    Videos: MP4, WEBM, OGG, MOV, AVI
                                </small>
                                <small class="text-muted d-block">
                                    <strong>Max size:</strong> 20MB
                                </small>
                            </div>
                        </div>

                    </div>

                    {{-- Right Side --}}
                    <div class="col-12 col-lg-4">
                        <div class="card border shadow-sm">
                            <div class="card-body p-3 p-sm-4">
                                <div class="mb-4 text-center">
                                    <label class="form-label fw-semibold d-block">Preview</label>
                                    <div class="border rounded p-3 bg-light" id="previewContainer">
                                        <div id="previewPlaceholder" class="text-center text-muted">
                                            <i class="bi bi-image fs-1 d-block mb-2"></i>
                                            <small>Image/Video preview will appear here</small>
                                        </div>
                                        <img id="imagePreview" class="img-fluid d-none" style="max-height: 150px; width: 100%; object-fit: contain;">
                                        <video id="videoPreview" class="img-fluid d-none" controls style="max-height: 150px; width: 100%;">
                                            <source id="videoSource" src="">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle me-1"></i> Create Banner
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- JavaScript for Live Preview --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.querySelector('input[name="image"]');
        const previewPlaceholder = document.getElementById('previewPlaceholder');
        const imagePreview = document.getElementById('imagePreview');
        const videoPreview = document.getElementById('videoPreview');
        const videoSource = document.getElementById('videoSource');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileURL = URL.createObjectURL(file);
                const fileType = file.type;
                
                // Reset previews
                imagePreview.classList.add('d-none');
                videoPreview.classList.add('d-none');
                previewPlaceholder.classList.remove('d-none');

                if (fileType.startsWith('video/')) {
                    // Video preview
                    videoSource.src = fileURL;
                    videoPreview.load();
                    videoPreview.classList.remove('d-none');
                    previewPlaceholder.classList.add('d-none');
                } else if (fileType.startsWith('image/')) {
                    // Image preview
                    imagePreview.src = fileURL;
                    imagePreview.classList.remove('d-none');
                    previewPlaceholder.classList.add('d-none');
                } else {
                    // Unsupported file type
                    previewPlaceholder.innerHTML = `
                        <i class="bi bi-exclamation-triangle fs-1 d-block mb-2 text-warning"></i>
                        <small class="text-danger">Unsupported file type</small>
                    `;
                }
            }
        });
    });
</script>
@endsection