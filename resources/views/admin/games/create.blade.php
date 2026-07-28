@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2 py-sm-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <div>
                    <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
                        <i class="bi bi-plus-circle me-2 text-primary"></i>Create New Game
                    </h4>
                    <p class="text-muted mb-0 small mt-1">Add a new game to the platform</p>
                </div>
                <a href="{{ route('admin.games.index') }}" class="btn btn-secondary btn-sm w-sm-auto">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body p-3 p-sm-4">
            <form method="POST"
                  action="{{ route('admin.games.store') }}"
                  enctype="multipart/form-data"
                  class="needs-validation"
                  novalidate>
                @csrf

                <div class="row g-3">
                    <div class="col-12 col-lg-8">

                        {{-- Game Name --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Game Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="name"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Enter game name"
                                   required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Platform --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Platform <span class="text-danger">*</span></label>
                            <select name="platform"
                                    id="platform_select"
                                    class="form-select form-select-lg @error('platform') is-invalid @enderror"
                                    required>
                                <option value="">Select Platform</option>
                                <option value="PC" {{ old('platform') == 'PC' ? 'selected' : '' }}>PC</option>
                                <option value="Mobile" {{ old('platform') == 'Mobile' ? 'selected' : '' }}>Mobile</option>
                                <option value="Console" {{ old('platform') == 'Console' ? 'selected' : '' }}>Console</option>
                                <option value="other" {{ old('platform') == 'other' ? 'selected' : '' }}>Other (Specify)</option>
                            </select>
                            @error('platform') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Custom Platform Text Input --}}
                        <div id="custom_platform_wrapper" style="display: {{ old('platform') == 'other' ? 'block' : 'none' }}; margin-bottom: 1rem;">
                            <label class="form-label fw-semibold">Specify Platform</label>
                            <input type="text"
                                   name="custom_platform"
                                   class="form-control @error('custom_platform') is-invalid @enderror"
                                   value="{{ old('custom_platform') }}"
                                   placeholder="e.g., PC and Console, Steam Deck, Cloud Gaming">
                            @error('custom_platform') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">Please type the exact platform name.</small>
                        </div>

                        {{-- Logo --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Game Logo</label>
                            <input type="file"
                                   name="logo"
                                   class="form-control @error('logo') is-invalid @enderror"
                                   accept="image/*">
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">Recommended: 200x200 pixels</small>
                        </div>

                        {{-- Banner --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Game Banner</label>
                            <input type="file"
                                   name="banner"
                                   class="form-control @error('banner') is-invalid @enderror"
                                   accept="image/*">
                            @error('banner') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">Recommended: 1200x400 pixels</small>
                        </div>
                    </div>

                    {{-- Right Side --}}
                    <div class="col-12 col-lg-4">
                        <div class="card border shadow-sm">
                            <div class="card-body p-3 p-sm-4">
                                {{-- Status --}}
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', '1') == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm ">
                                        <i class="bi bi-check-circle me-1"></i> Save Game
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

{{-- JavaScript for show/hide custom platform --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('platform_select');
        const wrapper = document.getElementById('custom_platform_wrapper');

        select.addEventListener('change', function() {
            if (this.value === 'other') {
                wrapper.style.display = 'block';
            } else {
                wrapper.style.display = 'none';
                document.querySelector('input[name="custom_platform"]').value = '';
            }
        });
    });
</script>
@endsection