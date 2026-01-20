@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-2">Tournament Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.tournaments.index') }}">Tournaments</a></li>
                    <li class="breadcrumb-item active">{{ $tournament->title }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-semibold">Tournament Information</h5>
                </div>
                <div class="card-body">

                    {{-- Title & Slug --}}
                    <div class="row mb-3">
                        <div class="col-md-6"><strong>Title:</strong> {{ $tournament->title }}</div>
                        <div class="col-md-6"><strong>Slug:</strong> {{ $tournament->slug }}</div>
                    </div>

                    {{-- Game & Format --}}
                    <div class="row mb-3">
                        <div class="col-md-6"><strong>Game:</strong> {{ $tournament->game->name ?? '-' }}</div>
                        <div class="col-md-6"><strong>Format:</strong> {{ ucfirst($tournament->format ?? '-') }}</div>
                    </div>

                    {{-- Team Size & Featured --}}
                    <div class="row mb-3">
                        <div class="col-md-6"><strong>Team Size:</strong> {{ $tournament->team_size ?? '-' }}</div>
                        <div class="col-md-6">
                            <strong>Featured:</strong>
                            <span class="badge {{ $tournament->is_featured ? 'bg-warning' : 'bg-secondary' }}">
                                {{ $tournament->is_featured ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>

                    {{-- Status & Visibility --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Status:</strong> 
                            <span class="badge 
                                {{ $tournament->status == 'live' ? 'bg-success' : ($tournament->status == 'upcoming' ? 'bg-warning' : 'bg-secondary') }}">
                                {{ ucfirst($tournament->status) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Visibility:</strong>
                            <span class="badge {{ $tournament->visibility == 'published' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($tournament->visibility) }}
                            </span>
                        </div>
                    </div>

                    {{-- Registration & Participants --}}
                    <div class="row mb-3">
                        <div class="col-md-6"><strong>Registration Open:</strong> {{ $tournament->is_registration_open ? 'Yes' : 'No' }}</div>
                        <div class="col-md-6"><strong>Registered Participants:</strong> {{ $tournament->registered_participants ?? 0 }}</div>
                    </div>

                    {{-- Fees & Prize --}}
                    <div class="row mb-3">
                        <div class="col-md-6"><strong>Entry Fee:</strong> {{ $tournament->entry_fee ?? 0 }}</div>
                        <div class="col-md-6"><strong>Prize Pool:</strong> {{ $tournament->prize_pool ?? 0 }}</div>
                    </div>

                    {{-- Max Participants & Location --}}
                    <div class="row mb-3">
                        <div class="col-md-6"><strong>Max Participants:</strong> {{ $tournament->max_participants ?? '-' }}</div>
                        <div class="col-md-6"><strong>Location:</strong> {{ $tournament->location ?? '-' }}</div>
                    </div>

                    {{-- Dates & Time --}}
                    <div class="row mb-3">
                        <div class="col-md-6"><strong>Start Date:</strong> {{ optional($tournament->start_date)->format('d M Y') ?? '-' }}</div>
                        <div class="col-md-6"><strong>End Date:</strong> {{ optional($tournament->end_date)->format('d M Y') ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Start Time:</strong> {{ $tournament->start_time ? \Carbon\Carbon::parse($tournament->start_time)->format('H:i') : '-' }}
                        </div>
                        <div class="col-md-6"><strong>Timezone:</strong> {{ $tournament->timezone ?? '-' }}</div>
                    </div>

                    {{-- Description & Rules --}}
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p>{{ $tournament->description ?? '-' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Rules:</strong>
                        <p>{{ $tournament->rules ?? '-' }}</p>
                    </div>

                </div>
            </div>
        </div>

        {{-- Sidebar: Preview --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-semibold">Preview</h5>
                </div>
                <div class="card-body text-center">
                    @if($tournament->banner)
                    <img src="{{ asset('storage/'.$tournament->banner) }}"
                         class="img-fluid rounded" style="max-height: 150px;">
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection