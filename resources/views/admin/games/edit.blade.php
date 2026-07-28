@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2 py-sm-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <div>
                    <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
                        <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Game
                    </h4>
                    <p class="text-muted mb-0 small mt-1">Update game details</p>
                </div>
                <a href="{{ route('admin.games.index') }}" class="btn btn-secondary btn-sm  w-sm-auto">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body p-3 p-sm-4">
            <form method="POST"
                  action="{{ route('admin.games.update', $game->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12 col-lg-8">

                        {{-- Name --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Game Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $game->name) }}"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
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
                                <option value="PC" {{ (old('platform', $game->platform) == 'PC') ? 'selected' : '' }}>PC</option>
                                <option value="Mobile" {{ (old('platform', $game->platform) == 'Mobile') ? 'selected' : '' }}>Mobile</option>
                                <option value="Console" {{ (old('platform', $game->platform) == 'Console') ? 'selected' : '' }}>Console</option>
                                <option value="other" {{ (!in_array(old('platform', $game->platform), ['PC','Mobile','Console'])) ? 'selected' : '' }}>Other (Specify)</option>
                            </select>
                            @error('platform') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Custom Platform Text Input --}}
                        @php
                            $isCustom = !in_array(old('platform', $game->platform), ['PC','Mobile','Console']);
                            $customValue = $isCustom ? old('platform', $game->platform) : old('custom_platform', '');
                        @endphp
                        <div id="custom_platform_wrapper" style="display: {{ $isCustom ? 'block' : 'none' }}; margin-bottom: 1rem;">
                            <label class="form-label fw-semibold">Specify Platform</label>
                            <input type="text"
                                   name="custom_platform"
                                   class="form-control @error('custom_platform') is-invalid @enderror"
                                   value="{{ $customValue }}"
                                   placeholder="e.g., PC and Console, Steam Deck, Cloud Gaming">
                            @error('custom_platform') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">Please type the exact platform name.</small>
                        </div>

                        {{-- Logo --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Game Logo</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            @if($game->logo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.$game->logo) }}" class="rounded" width="80" height="80" style="object-fit: cover;">
                                    <span class="text-muted small ms-2">Current logo</span>
                                </div>
                            @endif
                            <small class="text-muted">Leave empty to keep current logo</small>
                        </div>

                        {{-- Banner --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Game Banner</label>
                            <input type="file" name="banner" class="form-control" accept="image/*">
                            @if($game->banner)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.$game->banner) }}" class="rounded" width="120" height="60" style="object-fit: cover;">
                                    <span class="text-muted small ms-2">Current banner</span>
                                </div>
                            @endif
                            <small class="text-muted">Leave empty to keep current banner</small>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-12 col-lg-4">
                        <div class="card border shadow-sm">
                            <div class="card-body p-3 p-sm-4">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="1" {{ old('status', $game->status) ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $game->status) ? '' : 'selected' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-check-circle me-1"></i> Update Game
                                    </button>
                                    <a href="{{ route('admin.games.index') }}" class="btn btn-outline-secondary">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('platform_select');
        const wrapper = document.getElementById('custom_platform_wrapper');
        const customInput = document.querySelector('input[name="custom_platform"]');

        select.addEventListener('change', function() {
            if (this.value === 'other') {
                wrapper.style.display = 'block';
            } else {
                wrapper.style.display = 'none';
                customInput.value = '';
            }
        });
    });
</script>
@endsection