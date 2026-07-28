@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
        <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
            <i class="bi bi-trophy me-2"></i>Challenges
        </h4>
        <a href="{{ route('admin.challenge.create') }}" class="btn btn-primary btn-sm w-sm-auto">
            <i class="bi bi-plus-circle me-1"></i> Add Challenge
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Challenges Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" width="50">Sr.No.</th>
                            <th class="text-center">Welcome Heading</th>
                            <th class="text-center">Heading</th>
                            <th class="text-center" width="100">Image</th>
                            <th class="text-center" width="120">Video</th>
                            <th class="text-center" width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($challenges as $challenge)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                
                                <td>
                                    <span class="small">{{ Str::limit($challenge->welcome_heading, 30) }}</span>
                                </td>
                                
                                <td>
                                    <strong class="small">{{ $challenge->heading }}</strong>
                                </td>
                                
                                <td class="text-center">
                                    @if($challenge->image)
                                        <img src="{{ asset('storage/'.$challenge->image) }}" 
                                             class="rounded" 
                                             style="height:40px; width:60px; object-fit:cover;">
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                
                                <td class="text-center">
                                    @if($challenge->video_url)
                                        <a href="{{ $challenge->video_url }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-play-circle"></i> Watch
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                
                                <td>
                                    <div class="d-flex flex-column flex-sm-row gap-1 justify-content-center align-items-center">
                                        <a href="{{ route('admin.challenge.edit', $challenge->id) }}"
                                           class="btn btn-warning btn-sm w-100 w-sm-auto"
                                           data-bs-toggle="tooltip"
                                           title="Edit Challenge">
                                            <i class="bi bi-pencil-square"></i>
                                            <span class="d-none d-sm-inline"> Edit</span>
                                        </a>

                                        <form action="{{ route('admin.challenge.destroy', $challenge->id) }}"
                                              method="POST"
                                              class="d-inline w-100 w-sm-auto">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm w-100 w-sm-auto"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete Challenge"
                                                    onclick="return confirm('Are you sure you want to delete this challenge?')">
                                                <i class="bi bi-trash"></i>
                                                <span class="d-none d-sm-inline"> Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-trophy fs-1 text-muted mb-2"></i>
                                        <p class="mb-0 text-muted">No challenges found</p>
                                        <a href="{{ route('admin.challenge.create') }}" class="btn btn-primary btn-sm mt-3">
                                            <i class="bi bi-plus-circle me-1"></i> Add First Challenge
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($challenges instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="card-footer bg-white py-2 py-sm-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <small class="text-muted order-2 order-sm-1">
                    Showing {{ $challenges->firstItem() ?? 0 }} to {{ $challenges->lastItem() ?? 0 }}
                    of {{ $challenges->total() }} entries
                </small>
                <div class="order-1 order-sm-2 w-100 w-sm-auto">
                    {{ $challenges->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection