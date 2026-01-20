@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 fw-bold">
                    <i class="fas fa-trophy me-2 text-primary"></i>Edit Tournament
                </h2>
                <p class="text-muted mb-0">Update tournament details</p>
            </div>
            <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.tournaments.update', $tournament->id) }}"
                enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">

                        {{-- Tournament Title --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tournament Title *</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $tournament->title) }}" required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Slug --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Slug</label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                                value="{{ old('slug', $tournament->slug) }}">
                            @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Select Game --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Game</label>
                            <select name="game_id" class="form-select @error('game_id') is-invalid @enderror">
                                <option value="">Select Game</option>
                                @foreach($games as $game)
                                <option value="{{ $game->id }}"
                                    {{ old('game_id', $tournament->game_id) == $game->id ? 'selected' : '' }}>
                                    {{ $game->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('game_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Logo --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tournament Logo</label>
                            <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror"
                                accept="image/*">
                            @if($tournament->logo)
                            <img src="{{ asset('storage/'.$tournament->logo) }}" class="mt-2 rounded" width="100"
                                height="100">
                            @endif
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Banner --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tournament Banner</label>
                            <input type="file" name="banner" class="form-control @error('banner') is-invalid @enderror"
                                accept="image/*">
                            @if($tournament->banner)
                            <img src="{{ asset('storage/'.$tournament->banner) }}" class="mt-2 rounded" width="200"
                                height="100">
                            @endif
                            @error('banner') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Format --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Format</label>
                            <select name="format" class="form-select">
                                <option value="">Select Format</option>
                                <option value="solo"
                                    {{ old('format', $tournament->format) == 'solo' ? 'selected' : '' }}>Solo</option>
                                <option value="team"
                                    {{ old('format', $tournament->format) == 'team' ? 'selected' : '' }}>Team</option>
                            </select>
                        </div>

                        {{-- Team Size --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Team Size</label>
                            <input type="number" name="team_size" min="1" class="form-control"
                                value="{{ old('team_size', $tournament->team_size) }}">
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" rows="4"
                                class="form-control">{{ old('description', $tournament->description) }}</textarea>
                        </div>

                        {{-- Rules --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rules</label>
                            <textarea name="rules" rows="4"
                                class="form-control">{{ old('rules', $tournament->rules) }}</textarea>
                        </div>

                    </div>

                    {{-- Right Side --}}
                    <div class="col-md-4">
                        <div class="card border">
                            <div class="card-body">

                                {{-- Status --}}
                                <!-- <div class="mb-3">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="upcoming" {{ old('status', $tournament->status) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                        <option value="live" {{ old('status', $tournament->status) == 'live' ? 'selected' : '' }}>Live</option>
                                        <option value="completed" {{ old('status', $tournament->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div> -->

                                {{-- Visibility --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Visibility</label>
                                    <select name="visibility" class="form-select">
                                        <option value="draft"
                                            {{ old('visibility', $tournament->visibility) == 'draft' ? 'selected' : '' }}>
                                            Draft</option>
                                        <option value="published"
                                            {{ old('visibility', $tournament->visibility) == 'published' ? 'selected' : '' }}>
                                            Published</option>
                                        <option value="archived"
                                            {{ old('visibility', $tournament->visibility) == 'archived' ? 'selected' : '' }}>
                                            Archived</option>
                                    </select>
                                </div>

                                {{-- Featured --}}
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                                        value="1" {{ old('is_featured', $tournament->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_featured">
                                        Featured
                                    </label>
                                </div>

                                {{-- Registration --}}
                                <!-- <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_registration_open" id="is_registration_open" value="1" {{ old('is_registration_open', $tournament->is_registration_open) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_registration_open">
                                        Registration Open
                                    </label>
                                </div> -->

                                {{-- Registration Dates --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Registration Start</label>
                                    <input type="date" name="registration_start" class="form-control"
                                        value="{{ old('registration_start', optional($tournament->registration_start)->format('Y-m-d')) }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Registration End</label>
                                    <input type="date" name="registration_end" class="form-control"
                                        value="{{ old('registration_end', optional($tournament->registration_end)->format('Y-m-d')) }}">
                                </div>

                                {{-- Start / End Dates --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Start Date</label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ old('start_date', optional($tournament->start_date)->format('Y-m-d')) }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">End Date</label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ old('end_date', optional($tournament->end_date)->format('Y-m-d')) }}">
                                </div>

                                {{-- Start Time --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Start Time</label>
                                    <input type="time" name="start_time" class="form-control"
                                        value="{{ old('start_time', optional($tournament->start_time)->format('H:i')) }}">
                                </div>

                                {{-- Timezone --}}
                                <!-- <div class="mb-3">
                                    <label class="form-label fw-semibold">Timezone</label>
                                    <input type="text" name="timezone" class="form-control" value="{{ old('timezone', $tournament->timezone) }}">
                                </div> -->

                                {{-- Entry Fee --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Entry Fee</label>
                                    <input type="number" name="entry_fee" min="0" class="form-control"
                                        value="{{ old('entry_fee', $tournament->entry_fee) }}">
                                </div>

                                {{-- Prize Pool --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Prize Pool</label>
                                    <input type="number" name="prize_pool" min="0" class="form-control"
                                        value="{{ old('prize_pool', $tournament->prize_pool) }}">
                                </div>

                                {{-- Max Participants --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Max Participants</label>
                                    <input type="number" name="max_participants" min="1" class="form-control"
                                        value="{{ old('max_participants', $tournament->max_participants) }}">
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Update Tournament
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
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