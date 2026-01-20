@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 fw-bold">
                    <i class="fas fa-gamepad me-2 text-primary"></i>Edit Game
                </h2>
                <p class="text-muted mb-0">Update game details</p>
            </div>
            <a href="{{ route('admin.games.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card-body">
            <form method="POST"
                  action="{{ route('admin.games.update', $game->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">

                        {{-- Name --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Game Name *</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $game->name) }}"
                                   class="form-control"
                                   required>
                        </div>

                        {{-- Platform --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Platform *</label>
                            <select name="platform" class="form-select" required>
                                <option value="PC" {{ $game->platform == 'PC' ? 'selected' : '' }}>PC</option>
                                <option value="Mobile" {{ $game->platform == 'Mobile' ? 'selected' : '' }}>Mobile</option>
                                <option value="Console" {{ $game->platform == 'Console' ? 'selected' : '' }}>Console</option>
                            </select>
                        </div>

                        {{-- Logo --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Game Logo</label>
                            <input type="file" name="logo" class="form-control">
                            @if($game->logo)
                                <img src="{{ asset('storage/'.$game->logo) }}" class="mt-2" width="80">
                            @endif
                        </div>

                        {{-- Banner --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Game Banner</label>
                            <input type="file" name="banner" class="form-control">
                            @if($game->banner)
                                <img src="{{ asset('storage/'.$game->banner) }}" class="mt-2" width="120">
                            @endif
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-4">
                        <div class="card border">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="1" {{ $game->status ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ !$game->status ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-1"></i> Update Game
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection