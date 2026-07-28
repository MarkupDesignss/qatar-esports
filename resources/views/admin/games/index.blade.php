@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
        <div>
            <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
                <i class="bi bi-controller me-2"></i>Games Management
            </h4>
            <ol class="breadcrumb mb-0 mt-1 small">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Games</li>
            </ol>
        </div>
        @if (hasPermission('games.create'))
        <a href="{{ route('admin.games.create') }}" class="btn btn-primary btn-sm  w-sm-auto">
            <i class="bi bi-plus-circle me-1"></i> Add Game
        </a>
        @endif
    </div>

    {{-- Stats Cards --}}
    <div class="row g-2 g-sm-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body p-2 p-sm-3">
                    <div class="text-xs fw-bold text-primary text-uppercase mb-1 small">Total Games</div>
                    <div class="h5 fw-bold mb-0">{{ $games->total() }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body p-2 p-sm-3">
                    <div class="text-xs fw-bold text-success text-uppercase mb-1 small">Active Games</div>
                    <div class="h5 fw-bold mb-0">
                        {{ $games->where('status', 1)->count() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body p-2 p-sm-3">
                    <div class="text-xs fw-bold text-warning text-uppercase mb-1 small">Inactive Games</div>
                    <div class="h5 fw-bold mb-0">
                        {{ $games->where('status', 0)->count() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body p-2 p-sm-3">
                    <div class="text-xs fw-bold text-info text-uppercase mb-1 small">Total Tournaments</div>
                    <div class="h5 fw-bold mb-0">
                        {{ $games->sum('tournaments_count') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Games Table --}}
    <div class="card shadow-sm">
       

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" width="50">Sr.No.</th>
                            <th>Game</th>
                            <th class="d-none d-md-table-cell">Banner</th>
                            <th>Platform</th>
                            @if (hasPermission('games.edit'))
                            <th class="d-none d-sm-table-cell text-center">Status</th>
                            @endif
                            <th class="text-center">Tournaments</th>
                            <th class="text-end d-none d-lg-table-cell">Created</th>
                            @if (hasPermission('games.edit'))
                            <th class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($games as $game)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>

                                {{-- Game Info --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $game->logo ? asset('storage/' . $game->logo) : asset('images/no-image.jpg') }}"
                                            class="rounded d-none d-sm-block" width="40" height="40" style="object-fit: cover;">
                                        <div>
                                            <strong class="small">{{ $game->name }}</strong>
                                            <div class="d-md-none text-muted small">{{ $game->platform }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Banner --}}
                                <td class="d-none d-md-table-cell">
                                    <img src="{{ $game->banner ? asset('storage/'.$game->banner) : asset('images/no-image.jpg') }}"
                                         class="rounded" width="80" height="50" style="object-fit: cover;">
                                </td>

                                {{-- Platform --}}
                                <td>
                                    <span class="badge bg-info px-2 py-1 small">
                                        {{ $game->platform }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                @if (hasPermission('games.edit'))
                                <td class="d-none d-sm-table-cell text-center">
                                    <form method="POST"
                                          action="{{ route('admin.game.toggle-status', $game->id) }}"
                                          class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm {{ $game->status ? 'btn-success' : 'btn-danger' }} w-100">
                                            {{ $game->status ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                @endif

                                {{-- Total Tournaments --}}
                                <td class="text-center">
                                    @if($game->tournaments_count > 0)
                                        <a href="{{ route('admin.tournaments.index', ['game_id' => $game->id]) }}"
                                           class="badge bg-primary text-decoration-none" style="font-size: 14px;">
                                            {{ $game->tournaments_count }}
                                        </a>
                                    @else
                                        <span class="badge bg-secondary">0</span>
                                    @endif
                                </td>

                                {{-- Created --}}
                                <td class="text-end d-none d-lg-table-cell small">
                                    {{ optional($game->created_at)->format('d M Y') ?? '-' }}
                                </td>

                                {{-- Actions --}}
                                @if (hasPermission('games.edit'))
                                <td class="text-center">
                                    <div class="d-flex flex-column flex-sm-row gap-1 justify-content-center align-items-center">
                                        <a href="{{ route('admin.games.edit', $game->id) }}"
                                           class="btn btn-sm btn-outline-primary w-100 w-sm-auto"
                                           data-bs-toggle="tooltip"
                                           title="Edit Game">
                                            <i class="bi bi-pencil-square"></i>
                                            <span class="d-none d-sm-inline"> Edit</span>
                                        </a>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-controller fs-1 text-muted mb-2"></i>
                                        <p class="mb-0">No games found</p>
                                    </div>
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
                    Showing {{ $games->firstItem() ?? 0 }} to {{ $games->lastItem() ?? 0 }}
                    of {{ $games->total() }} entries
                </small>
                <div class="order-1 order-sm-2 w-100 w-sm-auto">
                    {{ $games->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 32px;
    height: 32px;
    font-size: 14px;
    flex-shrink: 0;
}
@media (max-width: 576px) {
    .avatar-circle {
        width: 28px;
        height: 28px;
        font-size: 12px;
    }
}
</style>
@endsection