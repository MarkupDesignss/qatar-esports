@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

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
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Add Challenge
                </h4>
                <a href="{{ route('admin.challenge.index') }}" class="btn btn-secondary btn-sm  w-sm-auto">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body p-3 p-sm-4">
            <form action="{{ route('admin.challenge.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="row g-3">
                    <div class="col-12 col-lg-8">

                        {{-- Welcome Heading --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Welcome Heading <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="welcome_heading" 
                                   class="form-control form-control-lg @error('welcome_heading') is-invalid @enderror" 
                                   value="{{ old('welcome_heading') }}"
                                   placeholder="Enter welcome heading"
                                   required>
                            @error('welcome_heading')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Heading --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Heading <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="heading" 
                                   class="form-control form-control-lg @error('heading') is-invalid @enderror" 
                                   value="{{ old('heading') }}"
                                   placeholder="Enter main heading"
                                   required>
                            @error('heading')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Content --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Content <span class="text-danger">*</span></label>
                            <textarea name="content" 
                                      id="content" 
                                      rows="5" 
                                      class="form-control @error('content') is-invalid @enderror" 
                                      placeholder="Enter challenge content"
                                      required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- Right Side --}}
                    <div class="col-12 col-lg-4">
                        <div class="card border shadow-sm">
                            <div class="card-body p-3 p-sm-4">

                                {{-- Image --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Image</label>
                                    <input type="file" 
                                           name="image" 
                                           class="form-control @error('image') is-invalid @enderror" 
                                           accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">Recommended: 800x600px</small>
                                </div>

                                {{-- Thumbnail --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Thumbnail</label>
                                    <input type="file" 
                                           name="thumbnail" 
                                           class="form-control @error('thumbnail') is-invalid @enderror" 
                                           accept="image/*">
                                    @error('thumbnail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">Recommended: 400x300px</small>
                                </div>

                                {{-- Video URL --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Video URL</label>
                                    <input type="url" 
                                           name="video_url" 
                                           class="form-control @error('video_url') is-invalid @enderror" 
                                           value="{{ old('video_url') }}"
                                           placeholder="https://www.youtube.com/watch?v=...">
                                    @error('video_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">YouTube, Vimeo, or any video URL</small>
                                </div>

                                {{-- Preview --}}
                                <div class="mb-3 text-center">
                                    <label class="form-label fw-semibold d-block">Image Preview</label>
                                    <div class="border rounded p-2 bg-light" id="previewContainer">
                                        <div id="previewPlaceholder" class="text-center text-muted">
                                            <i class="bi bi-image fs-1 d-block mb-2"></i>
                                            <small>Image preview</small>
                                        </div>
                                        <img id="imagePreview" class="img-fluid d-none" style="max-height: 120px; width: 100%; object-fit: cover; border-radius: 4px;">
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle me-1"></i> Save Challenge
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary ">
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

<!-- CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // CKEditor
        const textarea = document.getElementById('content');
        let editor;

        ClassicEditor.create(textarea, {
            toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', 'link', 'undo', 'redo']
        }).then(newEditor => {
            editor = newEditor;
        }).catch(error => console.error(error));

        const form = document.querySelector('form');
        form.addEventListener('submit', function() {
            if (editor) {
                editor.updateSourceElement();
            }
        });

        // Image Preview
        const fileInput = document.querySelector('input[name="image"]');
        const previewPlaceholder = document.getElementById('previewPlaceholder');
        const imagePreview = document.getElementById('imagePreview');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileURL = URL.createObjectURL(file);
                imagePreview.src = fileURL;
                imagePreview.classList.remove('d-none');
                previewPlaceholder.classList.add('d-none');
            }
        });
    });
</script>
@endsection