@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="h3 fw-bold text-gray-800 mb-2">Tournaments Management</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active">Tournaments</li>
            </ol>
        </div>

        <a href="{{ route('admin.tournaments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Add Tournament
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                        Total Tournaments
                    </div>
                    <div class="h5 fw-bold">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-xs fw-bold text-success text-uppercase mb-1">
                        Live
                    </div>
                    <div class="h5 fw-bold">{{ $stats['live'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                        Upcoming
                    </div>
                    <div class="h5 fw-bold">{{ $stats['upcoming'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-xs fw-bold text-info text-uppercase mb-1">
                        Featured
                    </div>
                    <div class="h5 fw-bold">{{ $stats['featured'] }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tournaments Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">Tournaments List</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>Tournament</th>
                            <th>Game</th>
                            <th>Banner</th>
                            <th>Format</th>
                            <th>Status</th>
                            <th>Visibility</th>
                            <th class="text-end">Start Date</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($tournaments as $tournament)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            {{-- Tournament Info --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $tournament->logo ? asset('storage/'.$tournament->logo) : asset('images/no-image.jpg') }}"
                                        class="rounded me-3" width="40" height="40">
                                    <div>
                                        <strong>{{ $tournament->title }}</strong><br>
                                        <small class="text-muted">{{ $tournament->slug }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Game --}}
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $tournament->game->name ?? '-' }}
                                </span>
                            </td>

                            {{-- Banner --}}
                            <td>
                                <img src="{{ $tournament->banner ? asset('storage/'.$tournament->banner) : asset('images/no-image.jpg') }}"
                                    class="rounded" width="100" height="60">
                            </td>

                            {{-- Format --}}
                            <td>
                                <span class="badge bg-info text-uppercase">
                                    {{ $tournament->format ?? '-' }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td>
                                @php
                                $status = $tournament->status;
                                @endphp
                                <span class="badge
                                        {{ $status == 'live' ? 'bg-success' :
                                        ($status == 'upcoming' ? 'bg-warning' : 'bg-secondary') }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>

                            {{-- Visibility --}}
                            <td>
                                <span class="badge 
                                        {{ $tournament->visibility == 'published' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($tournament->visibility) }}
                                </span>
                            </td>

                            {{-- Start Date --}}
                            <td class="text-end">
                                {{ optional($tournament->start_date)->format('d M Y') ?? '-' }}
                            </td>

                            {{-- Actions --}}
                            <td class="text-end">
                                <div class="btn-group" role="group">

                                    {{-- View --}}
                                    <a href="{{ route('admin.tournaments.show', $tournament->id) }}"
                                        class="btn btn-sm btn-outline-secondary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.tournaments.edit', $tournament->id) }}"
                                        class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    {{-- Toggle Featured --}}
                                    <form action="{{ route('admin.tournaments.toggle-featured', $tournament->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm {{ $tournament->is_featured ? 'btn-warning' : 'btn-outline-warning' }}"
                                            title="Toggle Featured">
                                            <i
                                                class="bi {{ $tournament->is_featured ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        </button>
                                    </form>

                                    {{-- Toggle Visibility --}}
                                    <form action="{{ route('admin.tournaments.toggle-visibility', $tournament->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm {{ $tournament->visibility == 'published' ? 'btn-success' : 'btn-outline-secondary' }}"
                                            title="Toggle Visibility">
                                            <i
                                                class="bi {{ $tournament->visibility == 'published' ? 'bi-eye-fill' : 'bi-eye-slash' }}"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                No tournaments found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Showing {{ $tournaments->firstItem() ?? 0 }} to {{ $tournaments->lastItem() ?? 0 }}
                    of {{ $tournaments->total() }} entries
                </small>
                {{ $tournaments->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

</div>
@endsection