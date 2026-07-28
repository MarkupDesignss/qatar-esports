@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
        <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
            <i class="bi bi-map me-2"></i>All Maps
        </h4>
        @if (hasPermission('maps.create'))
        <a href="{{ route('admin.maps.create') }}" class="btn btn-primary btn-sm w-sm-auto">
            <i class="bi bi-plus-circle me-1"></i> Create Map
        </a>
        @endif
    </div>

    {{-- Maps Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" width="50">Sr.No.</th>
                            <th>Game</th>
                            <th>Name</th>
                            <th class="d-none d-sm-table-cell">Image</th>
                            <th class="text-center d-none d-sm-table-cell">Status</th>
                            @if (hasPermission('maps.edit') || hasPermission('maps.delete'))
                            <th class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($maps as $map)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                
                                <td>
                                    <span class="small">{{ $map->game->name ?? '-' }}</span>
                                </td>
                                
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $map->image ? asset('storage/' . $map->image) : asset('images/no-image.jpg') }}" 
                                             class="rounded d-sm-none" width="30" height="30" style="object-fit: cover;">
                                        <strong class="small">{{ $map->name }}</strong>
                                    </div>
                                    <div class="d-sm-none mt-1">
                                        <span class="badge {{ $map->is_active ? 'bg-success' : 'bg-danger' }} small">
                                            {{ $map->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="d-none d-sm-table-cell">
                                    @if ($map->image)
                                        <img src="{{ asset('storage/' . $map->image) }}" 
                                             width="50" height="50" 
                                             class="rounded" style="object-fit: cover;">
                                    @else
                                        <span class="text-muted small">No image</span>
                                    @endif
                                </td>

                                <td class="text-center d-none d-sm-table-cell">
                                    <span class="badge {{ $map->is_active ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                                        {{ $map->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex flex-column flex-sm-row gap-1 justify-content-center align-items-center">
                                        @if (hasPermission('maps.edit'))
                                        <a href="{{ route('admin.maps.edit', $map->id) }}" 
                                           class="btn btn-warning btn-sm w-100 w-sm-auto">
                                            <i class="bi bi-pencil"></i>
                                            <span class="d-none d-sm-inline"> Edit</span>
                                        </a>
                                        @endif
                                        
                                        @if (hasPermission('maps.delete'))
                                        <form action="{{ route('admin.maps.destroy', $map->id) }}" 
                                              method="POST" 
                                              class="d-inline w-100 w-sm-auto">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm w-100 w-sm-auto" 
                                                    onclick="return confirm('Delete this map?')">
                                                <i class="bi bi-trash"></i>
                                                <span class="d-none d-sm-inline"> Delete</span>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-map fs-1 text-muted mb-2"></i>
                                        <p class="mb-0">No maps found</p>
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
                    Showing {{ $maps->firstItem() ?? 0 }} to {{ $maps->lastItem() ?? 0 }}
                    of {{ $maps->total() }} entries
                </small>
                <div class="order-1 order-sm-2 w-100 w-sm-auto">
                    {{ $maps->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection