@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
        <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
            <i class="bi bi-broadcast me-2"></i>Live Streams
        </h4>
        @if (hasPermission('livestream.create'))
        <a href="{{ route('admin.livestream.create') }}" class="btn btn-primary btn-sm w-sm-auto">
            <i class="bi bi-plus-circle me-1"></i> Add New Live Stream
        </a>
        @endif
    </div>

    {{-- Stats Cards --}}
    <div class="row g-2 g-sm-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body p-2 p-sm-3">
                    <div class="text-xs fw-bold text-primary text-uppercase mb-1 small">Total Streams</div>
                    <div class="h5 fw-bold mb-0">{{ $liveStreams->total() }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body p-2 p-sm-3">
                    <div class="text-xs fw-bold text-success text-uppercase mb-1 small">Live Now</div>
                    <div class="h5 fw-bold mb-0">
                        {{ $liveStreams->where('is_live', 1)->count() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body p-2 p-sm-3">
                    <div class="text-xs fw-bold text-warning text-uppercase mb-1 small">Upcoming</div>
                    <div class="h5 fw-bold mb-0">
                        {{ $liveStreams->where('is_live', 0)->count() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body p-2 p-sm-3">
                    <div class="text-xs fw-bold text-info text-uppercase mb-1 small">Platforms</div>
                    <div class="h5 fw-bold mb-0">
                        {{ $liveStreams->groupBy('platform')->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Live Streams Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-2 py-sm-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <h5 class="mb-0 fw-bold small">Streams List</h5>
                <div class="d-flex gap-2 w-100 w-sm-auto">
                    <input type="text" class="form-control form-control-sm" placeholder="Search streams..." 
                           id="searchStreams" style="min-width: 150px; max-width: 250px;">
                    <button class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" width="50">Sr.No.</th>
                            <th>Game</th>
                            <th class="d-none d-md-table-cell">Tournament</th>
                            <th>Channel</th>
                            <th class="d-none d-sm-table-cell">Platform</th>
                            <th class="d-none d-lg-table-cell">Language</th>
                            <th class="text-center d-none d-sm-table-cell">Live</th>
                            <th class="d-none d-md-table-cell">Video</th>
                            @if (hasPermission('livestream.edit') || hasPermission('livestream.delete'))
                            <th class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($liveStreams as $stream)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="small">{{ $stream->game->name ?? '-' }}</span>
                                    </div>
                                    <div class="d-md-none text-muted small mt-1">
                                        {{ $stream->tournament->title ?? '-' }}
                                    </div>
                                    <div class="d-sm-none text-muted small mt-1">
                                        <span class="badge {{ $stream->platform == 'YouTube' ? 'bg-danger' : ($stream->platform == 'Twitch' ? 'bg-purple' : 'bg-info') }}">
                                            {{ $stream->platform }}
                                        </span>
                                    </div>
                                </td>

                                <td class="d-none d-md-table-cell">
                                    <span class="small">{{ $stream->tournament->title ?? '-' }}</span>
                                </td>

                                <td>
                                    <strong class="small">{{ $stream->channel_name }}</strong>
                                    <div class="d-none d-sm-block text-muted small">{{ $stream->language }}</div>
                                </td>

                                <td class="d-none d-sm-table-cell">
                                    <span class="badge {{ $stream->platform == 'YouTube' ? 'bg-danger' : ($stream->platform == 'Twitch' ? 'bg-purple' : 'bg-info') }}">
                                        {{ $stream->platform }}
                                    </span>
                                </td>

                                <td class="d-none d-lg-table-cell">
                                    <span class="badge bg-secondary">{{ $stream->language }}</span>
                                </td>

                                <td class="text-center d-none d-sm-table-cell">
                                    <span class="badge {{ $stream->is_live ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                                        {{ $stream->is_live ? 'Live' : 'Offline' }}
                                    </span>
                                </td>

                                <td class="d-none d-md-table-cell">
                                    @if($stream->video_url)
                                        <a href="{{ $stream->video_url }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="bi bi-play-circle"></i> Watch
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex flex-column flex-sm-row gap-1 justify-content-center align-items-center">
                                        @if (hasPermission('livestream.edit'))
                                        <a href="{{ route('admin.livestream.edit', $stream->id) }}" 
                                           class="btn btn-warning btn-sm w-100 w-sm-auto" 
                                           data-bs-toggle="tooltip" 
                                           title="Edit Live Stream">
                                            <i class="bi bi-pencil-square"></i>
                                            <span class="d-none d-sm-inline"> Edit</span>
                                        </a>
                                        @endif
                                        
                                        @if (hasPermission('livestream.delete'))
                                        <form action="{{ route('admin.livestream.destroy', $stream->id) }}" 
                                              method="POST" 
                                              class="d-inline w-100 w-sm-auto">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-danger btn-sm w-100 w-sm-auto" 
                                                    onclick="return confirm('Are you sure you want to delete this live stream?')" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Delete Live Stream">
                                                <i class="bi bi-trash"></i>
                                                <span class="d-none d-sm-inline"> Delete</span>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                    <div class="d-sm-none mt-1 text-center">
                                        <span class="badge {{ $stream->is_live ? 'bg-success' : 'bg-danger' }} small">
                                            {{ $stream->is_live ? 'Live' : 'Offline' }}
                                        </span>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-broadcast fs-1 text-muted mb-2"></i>
                                        <p class="mb-0">No live streams found</p>
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
                    Showing {{ $liveStreams->firstItem() ?? 0 }} to {{ $liveStreams->lastItem() ?? 0 }}
                    of {{ $liveStreams->total() }} entries
                </small>
                <div class="order-1 order-sm-2 w-100 w-sm-auto">
                    {{ $liveStreams->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-purple {
    background-color: #9146FF !important;
    color: white !important;
}
</style>
@endsection