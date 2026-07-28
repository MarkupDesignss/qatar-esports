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
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Create News Type
                </h4>
                <a href="{{ route('admin.news-types.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body p-3 p-sm-4">
            <form action="{{ route('admin.news-types.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-12 col-lg-8">

                        {{-- Type Name --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Type Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="name"
                                   class="form-control form-control-sm @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Enter news type name"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Slug will be auto-generated from the name</small>
                        </div>

                    </div>

                    {{-- Right Side --}}
                    <div class="col-12 col-lg-4">
                        <div class="card border shadow-sm">
                            <div class="card-body p-3 p-sm-4">

                                {{-- Active --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Status</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" 
                                               name="is_active" 
                                               class="form-check-input" 
                                               value="1" 
                                               id="statusSwitch"
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="statusSwitch">
                                            <span id="statusLabel" class="badge {{ old('is_active', true) ? 'bg-success' : 'bg-danger' }}">
                                                {{ old('is_active', true) ? 'Active' : 'Inactive' }}
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Sort Order --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Sort Order</label>
                                    <input type="number" 
                                           name="sort_order" 
                                           class="form-control form-control-sm @error('sort_order') is-invalid @enderror" 
                                           value="{{ old('sort_order', 0) }}"
                                           placeholder="0 = highest">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">Lower numbers appear first</small>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle me-1"></i> Save Type
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Status Toggle
        const statusSwitch = document.getElementById('statusSwitch');
        const statusLabel = document.getElementById('statusLabel');

        statusSwitch.addEventListener('change', function() {
            if (this.checked) {
                statusLabel.textContent = 'Active';
                statusLabel.className = 'badge bg-success';
            } else {
                statusLabel.textContent = 'Inactive';
                statusLabel.className = 'badge bg-danger';
            }
        });
    });
</script>
@endsection