@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

        {{-- Page Header --}}
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
            <div>
                <h2 class="mb-0 h5 h4-sm fw-bold text-gray-800">
                    <i class="fas fa-trophy text-primary me-2"></i>Tournaments
                </h2>
                <p class="text-muted mb-0 small d-none d-sm-block">Manage all your tournaments from one place</p>
            </div>
            <a href="{{ route('admin.tournaments.create') }}" class="btn btn-primary btn-sm w-sm-auto">
                <i class="fas fa-plus me-1"></i> Add Tournament
            </a>
        </div>

        {{-- Stats Cards --}}
        <div class="row g-2 g-sm-3 mb-4">
            {{-- Total --}}
            <div class="col-6 col-lg-3">
                <div class="card bg-primary bg-opacity-10 border-0 h-100">
                    <div class="card-body p-2 p-sm-3 d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-trophy fa-1x fa-sm-2x text-primary"></i>
                        </div>
                        <div class="flex-grow-1 text-end">
                            <span class="text-muted small text-uppercase d-block" style="font-size: 0.65rem;">Total</span>
                            <span class="fs-4 fs-sm-3 fw-bold">{{ $stats['total'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Live --}}
            <div class="col-6 col-lg-3">
                <div class="card bg-success bg-opacity-10 border-0 h-100">
                    <div class="card-body p-2 p-sm-3 d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-broadcast fa-1x fa-sm-2x text-success"></i>
                        </div>
                        <div class="flex-grow-1 text-end">
                            <span class="text-muted small text-uppercase d-block" style="font-size: 0.65rem;">
                                Live
                                @if ($stats['live'] > 0)
                                    <span class="d-inline-block rounded-circle bg-success ms-1"
                                        style="width: 8px; height: 8px; animation: pulse 1.5s infinite;"></span>
                                @endif
                            </span>
                            <span class="fs-4 fs-sm-3 fw-bold">{{ $stats['live'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Upcoming --}}
            <div class="col-6 col-lg-3">
                <div class="card bg-warning bg-opacity-10 border-0 h-100">
                    <div class="card-body p-2 p-sm-3 d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-1x fa-sm-2x text-warning"></i>
                        </div>
                        <div class="flex-grow-1 text-end">
                            <span class="text-muted small text-uppercase d-block" style="font-size: 0.65rem;">Upcoming</span>
                            <span class="fs-4 fs-sm-3 fw-bold">{{ $stats['upcoming'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Featured --}}
            <div class="col-6 col-lg-3">
                <div class="card bg-info bg-opacity-10 border-0 h-100">
                    <div class="card-body p-2 p-sm-3 d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-star fa-1x fa-sm-2x text-info"></i>
                        </div>
                        <div class="flex-grow-1 text-end">
                            <span class="text-muted small text-uppercase d-block" style="font-size: 0.65rem;">Featured</span>
                            <span class="fs-4 fs-sm-3 fw-bold">{{ $stats['featured'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tournaments Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-2 py-sm-3 d-flex align-items-center">
                <i class="fas fa-list-ul text-primary me-2"></i>
                <span class="fw-semibold small">Tournaments List</span>
            </div>

            <div class="card-body p-0">
                {{-- Filter Section --}}
                <div class="p-2 p-sm-3 border-bottom bg-light">
                    <form method="GET" action="{{ route('admin.tournaments.index') }}" class="row g-2 align-items-end">
                        <div class="col-6 col-sm-4 col-md-2">
                            <label class="form-label small text-muted mb-0">Format</label>
                            <select name="format" class="form-select form-select-sm">
                                <option value="">Any</option>
                                <option value="solo" {{ request('format') == 'solo' ? 'selected' : '' }}>Solo</option>
                                <option value="team" {{ request('format') == 'team' ? 'selected' : '' }}>Team</option>
                            </select>
                        </div>

                        <div class="col-6 col-sm-4 col-md-2">
                            <label class="form-label small text-muted mb-0">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Any</option>
                                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="live" {{ request('status') == 'live' ? 'selected' : '' }}>Live</option>
                                <option value="past" {{ request('status') == 'past' ? 'selected' : '' }}>Past</option>
                            </select>
                        </div>

                        <div class="col-6 col-sm-4 col-md-2">
                            <label class="form-label small text-muted mb-0">Start From</label>
                            <input type="date" name="date_from" class="form-control form-control-sm"
                                value="{{ request('date_from') ?? request('start_date') }}">
                        </div>

                        <div class="col-6 col-sm-4 col-md-2">
                            <label class="form-label small text-muted mb-0">End To</label>
                            <input type="date" name="date_to" class="form-control form-control-sm"
                                value="{{ request('date_to') ?? request('end_date') }}">
                        </div>

                        <div class="col-12 col-sm-4 col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-secondary btn-sm flex-grow-1">
                                <i class="fas fa-undo me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Table - Fully Responsive with all columns visible --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Tournament</th>
                                <th>Game</th>
                                <th class="text-center" style="width: 80px;">Banner</th>
                                <th class="text-center">Format</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Visibility</th>
                                <th class="text-center" style="min-width: 140px;">Start Date</th>
                                <th class="text-center" style="min-width: 220px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tournaments as $tournament)
                                <tr>
                                    {{-- Sr No --}}
                                    <td class="text-center">{{ $loop->iteration }}</td>

                                    {{-- Tournament Info --}}
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $tournament->logo ? asset('storage/' . $tournament->logo) : asset('images/no-image.jpg') }}"
                                                class="rounded me-2" width="32" height="32"
                                                style="object-fit: cover; flex-shrink: 0;">
                                            <div>
                                                <span class="fw-semibold">{{ Str::limit($tournament->title, 25) }}</span>
                                                <small class="d-block text-muted">{{ Str::limit($tournament->slug, 20) }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Game --}}
                                    <td>
                                        <span class="badge bg-secondary">{{ $tournament->game->name ?? '-' }}</span>
                                    </td>

                                    {{-- Banner --}}
                                    <td class="text-center">
                                        <img src="{{ $tournament->banner ? asset('storage/' . $tournament->banner) : asset('images/no-image.jpg') }}"
                                            width="50" height="35" class="rounded" style="object-fit: cover;">
                                    </td>

                                    {{-- Format --}}
                                    <td class="text-center">
                                        <span class="badge bg-info bg-opacity-10 text-info text-uppercase">{{ $tournament->format ?? '-' }}</span>
                                    </td>

                                    {{-- Status --}}
                                    <td class="text-center">
                                        @php $status = $tournament->status; @endphp
                                        <span class="badge {{ $status == 'live' ? 'bg-success' : ($status == 'upcoming' ? 'bg-warning' : 'bg-secondary') }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>

                                    {{-- Visibility --}}
                                    <td class="text-center">
                                        <span class="badge {{ $tournament->visibility == 'published' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($tournament->visibility) }}
                                        </span>
                                    </td>

                                    {{-- Start Date --}}
                                    <td class="text-center">
                                        <small>{{ optional($tournament->start_date)->format('d M Y, h:i A') ?? '-' }}</small>
                                    </td>

                                    {{-- Actions - All 5 buttons in single line on large screens --}}
                                    <td>
                                        <div class="d-flex flex-wrap flex-lg-nowrap gap-1 justify-content-center">
                                            {{-- View --}}
                                            @if (hasPermission('tournament.view'))
                                                <a href="{{ route('admin.tournaments.show', $tournament->id) }}"
                                                    class="btn btn-sm btn-outline-primary flex-shrink-0" 
                                                    data-bs-toggle="tooltip"
                                                    title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif

                                            {{-- Edit --}}
                                            @if (hasPermission('tournament.edit'))
                                                <a href="{{ route('admin.tournaments.edit', $tournament->id) }}"
                                                    class="btn btn-sm btn-outline-warning flex-shrink-0" 
                                                    data-bs-toggle="tooltip"
                                                    title="Edit Tournament">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif

                                            {{-- Export --}}
                                            @if (hasPermission('tournament.export'))
                                                <a href="{{ route('admin.tournaments.export-participants', $tournament->id) }}"
                                                    class="btn btn-sm btn-outline-success flex-shrink-0" 
                                                    data-bs-toggle="tooltip"
                                                    title="Export Participants">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif

                                            {{-- Toggle Featured --}}
                                            @if (hasPermission('tournament.freatured'))
                                                <form action="{{ route('admin.tournaments.toggle-featured', $tournament->id) }}"
                                                    method="POST" class="d-inline flex-shrink-0">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-sm {{ $tournament->is_featured ? 'btn-warning' : 'btn-outline-secondary' }} flex-shrink-0"
                                                        data-bs-toggle="tooltip"
                                                        title="{{ $tournament->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Toggle Visibility --}}
                                            @if (hasPermission('tournament.edit'))
                                                <form action="{{ route('admin.tournaments.toggle-visibility', $tournament->id) }}"
                                                    method="POST" class="d-inline flex-shrink-0">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-sm {{ $tournament->visibility == 'published' ? 'btn-success' : 'btn-outline-secondary' }} flex-shrink-0"
                                                        data-bs-toggle="tooltip"
                                                        title="{{ $tournament->visibility == 'published' ? 'Hide Tournament' : 'Publish Tournament' }}">
                                                        <i class="fas {{ $tournament->visibility == 'published' ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="fas fa-trophy fa-3x text-muted d-block mb-2"></i>
                                        <h5 class="text-muted">No Tournaments Found</h5>
                                        <p class="text-muted small">Click "Add Tournament" to create one.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="card-footer bg-white py-2 py-sm-3">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                    <small class="text-muted order-2 order-sm-1">
                        Showing {{ $tournaments->firstItem() ?? 0 }} to {{ $tournaments->lastItem() ?? 0 }}
                        of {{ $tournaments->total() }} entries
                    </small>
                    <div class="order-1 order-sm-2 w-100 w-sm-auto">
                        {{ $tournaments->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes pulse {
            0% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.5;
                transform: scale(1.3);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Responsive Font Sizes */
        .fa-1x { font-size: 1.25rem; }
        .fs-4 { font-size: 1.25rem; }
        
        @media (min-width: 576px) {
            .fa-sm-2x { font-size: 2rem; }
            .fs-sm-3 { font-size: 1.75rem; }
        }

        /* Mobile Optimizations - Make table scrollable but all columns visible */
        @media (max-width: 991.98px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .table {
                min-width: 900px; /* Ensures all columns are visible when scrolling */
            }
            
            .table td, .table th {
                font-size: 0.75rem;
                padding: 0.4rem 0.5rem;
                white-space: nowrap;
            }
            
            .btn-sm {
                font-size: 0.65rem;
                padding: 0.2rem 0.4rem;
            }
            
            .badge {
                font-size: 0.6rem;
                padding: 0.25rem 0.4rem;
            }
        }

        /* Very small screens */
        @media (max-width: 575.98px) {
            .table td, .table th {
                font-size: 0.7rem;
                padding: 0.3rem 0.4rem;
            }
            
            .table {
                min-width: 800px;
            }
            
            .btn-sm {
                font-size: 0.6rem;
                padding: 0.15rem 0.3rem;
            }
        }

        /* Tablet optimizations */
        @media (min-width: 992px) {
            .flex-lg-nowrap {
                flex-wrap: nowrap !important;
            }
            
            .table td:last-child {
                white-space: nowrap;
            }
        }

        /* Truncate long text */
        .min-width-0 {
            min-width: 0;
            overflow: hidden;
        }

        /* Action buttons on mobile */
        .table td:last-child {
            min-width: 180px;
        }
    </style>

    {{-- Tooltip Init --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltips.map(function(el) {
                return new bootstrap.Tooltip(el);
            });
        });
    </script>
@endsection