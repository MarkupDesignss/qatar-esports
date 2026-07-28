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
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Add Match Highlight
                </h4>
                <a href="{{ route('admin.matches.index') }}" class="btn btn-secondary btn-sm w-sm-auto">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body p-3 p-sm-4">
            <form action="{{ route('admin.matches.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  novalidate>
                @csrf

                <div class="row g-3">
                    <div class="col-12 col-lg-8">

                        {{-- Title --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="title"
                                   value="{{ old('title') }}"
                                   class="form-control form-control-lg @error('title') is-invalid @enderror"
                                   placeholder="Enter match title"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Short Description</label>
                            <textarea name="description"
                                      id="description"
                                      class="form-control rich-editor"
                                      rows="3"
                                      placeholder="Enter short description">{{ old('description') }}</textarea>
                        </div>

                        {{-- Video Title --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Video Title</label>
                            <input type="text"
                                   name="video_title"
                                   value="{{ old('video_title') }}"
                                   class="form-control @error('video_title') is-invalid @enderror"
                                   placeholder="Enter video title">
                            @error('video_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Video URL --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Video URL</label>
                            <input type="url"
                                   name="video_url"
                                   value="{{ old('video_url') }}"
                                   class="form-control @error('video_url') is-invalid @enderror"
                                   placeholder="https://www.youtube.com/watch?v=...">
                            @error('video_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">YouTube, Vimeo, or any video URL</small>
                        </div>

                    </div>

                    {{-- Right Side --}}
                    <div class="col-12 col-lg-4">
                        <div class="card border shadow-sm">
                            <div class="card-body p-3 p-sm-4">

                                {{-- Thumbnail --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Thumbnail <span class="text-danger">*</span></label>
                                    <input type="file" 
                                           name="thumbnail" 
                                           class="form-control @error('thumbnail') is-invalid @enderror" 
                                           accept="image/*"
                                           required>
                                    @error('thumbnail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">Recommended: 800x450px</small>
                                </div>

                                {{-- Type --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                                    <select name="type"
                                            class="form-select @error('type') is-invalid @enderror"
                                            required>
                                        <option value="">Select Type</option>
                                        @foreach(['all','match_highlights','press_release','media','blogs'] as $type)
                                            <option value="{{ $type }}"
                                                {{ old('type') === $type ? 'selected' : '' }}>
                                                {{ ucwords(str_replace('_',' ',$type)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Gallery Images --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Gallery Images <span class="text-danger">*</span></label>
                                    <input type="file"
                                           name="images[]"
                                           class="form-control @error('images.*') is-invalid @enderror"
                                           multiple
                                           required>
                                    @error('images.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">
                                        <i class="bi bi-info-circle me-1"></i>
                                        You can upload multiple images (JPG, PNG, WEBP)
                                    </small>
                                </div>

                                {{-- Preview --}}
                                <div class="mb-3 text-center">
                                    <label class="form-label fw-semibold d-block">Thumbnail Preview</label>
                                    <div class="border rounded p-2 bg-light" id="previewContainer">
                                        <div id="previewPlaceholder" class="text-center text-muted">
                                            <i class="bi bi-image fs-1 d-block mb-2"></i>
                                            <small>Thumbnail preview</small>
                                        </div>
                                        <img id="imagePreview" class="img-fluid d-none" style="max-height: 120px; width: 100%; object-fit: cover; border-radius: 4px;">
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle me-1"></i> Save Match
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

<!-- CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
    let editors = [];

    function initEditor(textarea) {
        if (textarea && !textarea.hasAttribute('data-editor')) {
            ClassicEditor.create(textarea, {
                toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', 'link', 'undo', 'redo']
            }).then(editor => {
                editors.push(editor);
                textarea.setAttribute('data-editor', 'true');
            }).catch(error => console.error(error));
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize CKEditor
        document.querySelectorAll('.rich-editor').forEach(textarea => {
            initEditor(textarea);
        });

        // Sync editors before form submission
        const form = document.querySelector('form');
        form.addEventListener('submit', function() {
            editors.forEach(editor => {
                editor.updateSourceElement();
            });
        });

        // Thumbnail Preview
        const fileInput = document.querySelector('input[name="thumbnail"]');
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