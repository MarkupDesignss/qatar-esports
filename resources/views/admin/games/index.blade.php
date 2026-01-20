@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="h3 fw-bold text-gray-800 mb-2">Games Management</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active">Games</li>
            </ol>
        </div>

        <a href="{{ route('admin.games.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Add Game
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                        Total Games
                    </div>
                    <div class="h5 fw-bold">{{ $games->total() }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-xs fw-bold text-success text-uppercase mb-1">
                        Active Games
                    </div>
                    <div class="h5 fw-bold">
                        {{ $games->where('status', 1)->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Games Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">Games List</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>Game</th>
                            <th>Banner</th>
                            <th>Platform</th>
                            <th>Status</th>
                            <th class="text-end">Created</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($games as $game)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                {{-- Game Info --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $game->logo ? asset('storage/'.$game->logo) : asset('images/no-image.jpg') }}"
                                             class="rounded me-3"
                                             width="40" height="40">
                                        <div>
                                            <strong>{{ $game->name }}</strong><br>
                                            <small class="text-muted">{{ $game->slug }}</small>
                                        </div>
                                    </div>
                                </td>

                                 <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $game->banner ? asset('storage/'.$game->banner) : asset('images/no-image.jpg') }}"
                                             class="rounded me-3"
                                             width="100" height="100">
                                    </div>
                                </td>

                                {{-- Platform --}}
                                <td>
                                    <span class="badge bg-info">
                                        {{ $game->platform }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td>
                                    <form method="POST"
                                          action="{{ route('admin.game.toggle-status', $game->id) }}">
                                        @csrf
                                        <button
                                            class="btn btn-sm {{ $game->status ? 'btn-success' : 'btn-danger' }}">
                                            {{ $game->status ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>

                                {{-- Created --}}
                                <td class="text-end">
                                  {{ optional($game->created_at)->format('d M Y') ?? '-' }}

                                </td>

                                {{-- Actions --}}
                                <td class="text-end">
    <a href="{{ route('admin.games.edit', $game->id) }}"
       class="btn btn-sm btn-outline-primary"
       data-bs-toggle="tooltip"
       title="Edit Game">
        <i class="bi bi-pencil-square"></i>
    </a>
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    No games found
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
                    Showing {{ $games->firstItem() ?? 0 }} to {{ $games->lastItem() ?? 0 }}
                    of {{ $games->total() }} entries
                </small>
                {{ $games->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection