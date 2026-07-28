@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    {{-- Page Header --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4 gap-3 gap-lg-0">
        <div>
            <h2 class="mb-0 h5 h4-sm fw-bold text-gray-800">Tournament Details</h2>
        </div>
        <div class=" w-lg-auto">
            <a href="{{ route('admin.tournaments.index') }}" class="btn btn-sm btn-outline-secondary bg-secondary text-white w-sm-auto d-flex align-items-center justify-content-center">
                <i class="fas fa-arrow-left me-2"></i>Back
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
                            <span
                                class="badge 
                                {{ $tournament->status == 'live' ? 'bg-success' : ($tournament->status == 'upcoming' ? 'bg-warning' : 'bg-secondary') }}">
                                {{ ucfirst($tournament->status) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Visibility:</strong>
                            <span
                                class="badge {{ $tournament->visibility == 'published' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($tournament->visibility) }}
                            </span>
                        </div>
                    </div>

                    {{-- Registration & Participants --}}
                    <div class="row mb-3">
                        <div class="col-md-6"><strong>Registration Open:</strong>
                            {{ $tournament->is_registration_open ? 'Yes' : 'No' }}</div>
                        <div class="col-md-6"><strong>Registered Participants:</strong>
                            {{ $tournament->registered_participants ?? 0 }}</div>
                    </div>

                    {{-- Fees & Prize --}}
                    <div class="row mb-3">
                        <div class="col-md-6"><strong>Entry Fee:</strong> {{ $tournament->entry_fee ?? 0 }}</div>
                        <div class="col-md-6"><strong>Prize Pool:</strong> {{ $tournament->prize_pool ?? 0 }}</div>
                    </div>

                    {{-- PDF Allowed & Max Participants --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>PDF Allowed:</strong>
                            <span class="badge {{ $tournament->allow_pdf_download ? 'bg-warning' : 'bg-secondary' }}">
                                {{ $tournament->allow_pdf_download ? 'Yes' : 'No' }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Max Participants:</strong> {{ $tournament->max_participants ?? '-' }}
                        </div>
                    </div>

                    {{-- Dates & Time --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Registration Start:</strong><br>
                            {{ optional($tournament->registration_start)->format('d M Y, h:i A') ?? '-' }}
                        </div>

                        <div class="col-md-6">
                            <strong>Registration End:</strong><br>
                            {{ optional($tournament->registration_end)->format('d M Y, h:i A') ?? '-' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Playoff Start:</strong><br>
                            {{ optional($tournament->start_date)->format('d M Y, h:i A') ?? '-' }}
                        </div>

                        <div class="col-md-6">
                            <strong>Playoff End:</strong><br>
                            {{ optional($tournament->end_date)->format('d M Y, h:i A') ?? '-' }}
                        </div>
                    </div>

                    {{-- Description & Rules --}}
                    <div class="mb-3">
                    <strong>Description:</strong>
                    <div>{!! $tournament->description ?? '-' !!}</div>
                </div>
                <div class="mb-3">
                    <strong>Rules:</strong>
                    <div>{!! $tournament->rules ?? '-' !!}</div>
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
                    <img src="{{ asset('storage/'.$tournament->banner) }}" class="img-fluid rounded"
                        style="max-height: 150px;">
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection