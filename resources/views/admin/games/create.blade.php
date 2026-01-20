@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 fw-bold">
                    <i class="fas fa-gamepad me-2 text-primary"></i>Create New Game
                </h2>
                <p class="text-muted mb-0">Add a new game to the platform</p>
            </div>
            <a href="{{ route('admin.games.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card-body">
            <form method="POST"
                  action="{{ route('admin.games.store') }}"
                  enctype="multipart/form-data"
                  class="needs-validation"
                  novalidate>
                @csrf

                <div class="row">
                    <div class="col-md-8">

                        {{-- Game Name --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Game Name *</label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Platform --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Platform *</label>
                            <select name="platform"
                                    class="form-select @error('platform') is-invalid @enderror"
                                    required>
                                <option value="">Select Platform</option>
                                <option value="PC">PC</option>
                                <option value="Mobile">Mobile</option>
                                <option value="Console">Console</option>
                            </select>
                            @error('platform') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Logo --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Game Logo</label>
                            <input type="file"
                                   name="logo"
                                   class="form-control @error('logo') is-invalid @enderror"
                                   accept="image/*">
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Banner --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Game Banner</label>
                            <input type="file"
                                   name="banner"
                                   class="form-control @error('banner') is-invalid @enderror"
                                   accept="image/*">
                            @error('banner') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Right Side --}}
                    <div class="col-md-4">
                        <div class="card border">
                            <div class="card-body">
                                {{-- Status --}}
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Save Game
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary">
                                        Reset
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
@endsection
