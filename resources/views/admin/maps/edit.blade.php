@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2 py-sm-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Map
                </h4>
                <a href="{{ route('admin.maps.index') }}" class="btn btn-secondary btn-sm w-sm-auto">
                    <i class="bi bi-arrow-left me-1"></i> Back to Maps
                </a>
            </div>
        </div>

        <div class="card-body p-3 p-sm-4">
            <form action="{{ route('admin.maps.update', $map->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12 col-lg-8">

                        {{-- Game Selection --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Game <span class="text-danger">*</span></label>
                            <select name="game_id" class="form-select form-select-lg @error('game_id') is-invalid @enderror" required>
                                <option value="">Select Game</option>
                                @foreach ($games as $id => $name)
                                    <option value="{{ $id }}" {{ old('game_id', $map->game_id) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('game_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Map Name --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Map Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $map->name) }}"
                                   placeholder="Enter map name"
                                   required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Image Upload --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Map Image</label>
                            <input type="file" 
                                   name="image" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   accept="image/*">
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            
                            @if ($map->image)
                                <div class="mt-2">
                                    <label class="form-label small text-muted">Current Image:</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ asset('storage/' . $map->image) }}" 
                                             width="80" height="80" 
                                             class="rounded border" style="object-fit: cover;">
                                        <span class="text-muted small">Leave empty to keep current image</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>

                    {{-- Right Side --}}
                    <div class="col-12 col-lg-4">
                        <div class="card border shadow-sm">
                            <div class="card-body p-3 p-sm-4">
                                {{-- Status --}}
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select name="is_active" class="form-select">
                                        <option value="1" {{ old('is_active', $map->is_active) ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active', $map->is_active) ? '' : 'selected' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-check-circle me-1"></i> Update Map
                                    </button>
                                    <a href="{{ route('admin.maps.index') }}" class="btn btn-outline-secondary">
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
@endsection