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
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Edit {{ ucfirst($page->type) }} Page
                </h4>
                <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body p-3 p-sm-4">
            <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12 col-lg-8">

                        {{-- Title --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Page Title <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="title" 
                                   class="form-control form-control-sm @error('title') is-invalid @enderror" 
                                   value="{{ old('title', $page->title) }}"
                                   placeholder="Enter page title"
                                   required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Slug (read-only) --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Slug (URL Identifier)</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="text" 
                                       class="form-control form-control-sm bg-light" 
                                       value="{{ $page->slug }}" 
                                       disabled>
                                <span class="badge bg-info text-white small">Read-only</span>
                            </div>
                            <small class="text-muted d-block mt-1">
                                <i class="bi bi-info-circle me-1"></i>
                                Slug is used for the URL and cannot be changed.
                            </small>
                        </div>

                        {{-- Content (CKEditor) --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Content <span class="text-danger">*</span></label>
                            <textarea name="content" 
                                      id="content" 
                                      rows="15" 
                                      class="form-control @error('content') is-invalid @enderror"
                                      required>{{ old('content', $page->content) }}</textarea>
                            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                    </div>

                    {{-- Right Side --}}
                    <div class="col-12 col-lg-4">
                        <div class="card border shadow-sm">
                            <div class="card-body p-3 p-sm-4">

                                {{-- Page Info --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Page Information</label>
                                    <div class="border rounded p-2 bg-light">
                                        <div class="d-flex justify-content-between py-1">
                                            <span class="text-muted small">Type:</span>
                                            <span class="fw-semibold small text-capitalize">{{ $page->type }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between py-1 border-top">
                                            <span class="text-muted small">Created:</span>
                                            <span class="small">{{ $page->created_at->format('d M Y h:i A') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between py-1 border-top">
                                            <span class="text-muted small">Last Updated:</span>
                                            <span class="small">{{ $page->updated_at->format('d M Y h:i A') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between py-1 border-top">
                                            <span class="text-muted small">Word Count:</span>
                                            <span class="small">{{ number_format(str_word_count(strip_tags($page->content))) }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Page Preview Link --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Preview</label>
                                    <div class="border rounded p-2 bg-light">
                                        <a href="{{ url('/' . $page->slug) }}" target="_blank" class="text-decoration-none small">
                                            <i class="bi bi-eye me-1"></i> View Page
                                        </a>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-check-circle me-1"></i> Update Page
                                    </button>
                                    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="bi bi-x-circle me-1"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- CKEditor 5 CDN --}}
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let editorInstance;
        const textarea = document.getElementById('content');

        if (textarea) {
            ClassicEditor.create(textarea, {
                toolbar: [
                    'heading', 'bold', 'italic', 'underline', 'strikethrough',
                    'bulletedList', 'numberedList', 'link', 'blockQuote',
                    'undo', 'redo', 'removeFormat'
                ]
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
    });
</script>

<style>
    /* Responsive CKEditor */
    .ck-editor__editable {
        min-height: 300px;
        max-height: 500px;
    }
    @media (max-width: 576px) {
        .ck-editor__editable {
            min-height: 200px;
            max-height: 350px;
        }
        .ck-toolbar {
            flex-wrap: wrap !important;
        }
        .ck-toolbar .ck-toolbar__items {
            flex-wrap: wrap !important;
        }
    }
</style>
@endsection