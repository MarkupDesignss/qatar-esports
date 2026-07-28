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
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Create News
                </h4>
                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body p-3 p-sm-4">
            <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="row g-3">
                    <div class="col-12 col-lg-8">

                        {{-- Title --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="title"
                                   class="form-control form-control-sm @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}"
                                   placeholder="Enter news title"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            <textarea name="description"
                                      id="description"
                                      rows="5"
                                      class="form-control form-control-sm @error('description') is-invalid @enderror"
                                      placeholder="Enter news description"
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- Right Side --}}
                    <div class="col-12 col-lg-4">
                        <div class="card border shadow-sm">
                            <div class="card-body p-3 p-sm-4">

                                {{-- Tournament --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Tournament</label>
                                    <select name="tournament_id" class="form-select form-select-sm @error('tournament_id') is-invalid @enderror">
                                        <option value="">-- Select Tournament --</option>
                                        @foreach($tournaments as $id => $name)
                                            <option value="{{ $id }}" {{ old('tournament_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tournament_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Type --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                                    <select name="type_id" class="form-select form-select-sm @error('type_id') is-invalid @enderror" required>
                                        <option value="">-- Select Type --</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Thumbnail --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Thumbnail</label>
                                    <input type="file" 
                                           name="thumbnail" 
                                           class="form-control form-control-sm @error('thumbnail') is-invalid @enderror">
                                    @error('thumbnail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">Recommended: 800x450px</small>
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

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle me-1"></i> Save News
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary btn-sm">
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
        let editorInstance;
        const textarea = document.getElementById('description');

        if (textarea) {
            ClassicEditor.create(textarea, {
                toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', 'link', 'undo', 'redo']
            }).then(editor => {
                editorInstance = editor;
            }).catch(error => console.error(error));
        }

        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function() {
                if (editorInstance) {
                    editorInstance.updateSourceElement();
                }
            });
        }

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