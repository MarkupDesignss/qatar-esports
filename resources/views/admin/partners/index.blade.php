@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
        <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
            <i class="bi bi-building me-2"></i>Partners
        </h4>
        <a href="{{ route('admin.partners.create') }}" class="btn btn-primary btn-sm w-sm-auto">
            <i class="bi bi-plus-circle me-1"></i> Add Partner
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="row g-2 g-sm-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body p-2 p-sm-3">
                    <div class="text-xs fw-bold text-primary text-uppercase mb-1 small">Total Partners</div>
                    <div class="h5 fw-bold mb-0">{{ $partners->count() }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body p-2 p-sm-3">
                    <div class="text-xs fw-bold text-success text-uppercase mb-1 small">Active Partners</div>
                    <div class="h5 fw-bold mb-0">
                        {{ $partners->where('status', 1)->count() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body p-2 p-sm-3">
                    <div class="text-xs fw-bold text-warning text-uppercase mb-1 small">Inactive Partners</div>
                    <div class="h5 fw-bold mb-0">
                        {{ $partners->where('status', 0)->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Partners Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" width="50">Sr.No.</th>
                            <th class="text-center" width="80">Logo</th>
                            <th class="text-center">Name</th>
                            <th class="text-center" width="100">Status</th>
                            <th class="text-center" width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($partners as $partner)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                
                                <td class="text-center">
                                    <img src="{{ asset('storage/'.$partner->logo) }}" 
                                         class="rounded" 
                                         style="height:40px; width:40px; object-fit:contain;">
                                </td>
                                
                                <td class="text-center">
                                    <strong class="small">{{ $partner->name }}</strong>
                                </td>
                                
                                <td class="text-center">
                                    <span class="badge {{ $partner->status ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                                        <i class="bi {{ $partner->status ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                        {{ $partner->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                
                                <td>
                                    <div class="d-flex flex-column flex-sm-row gap-1 justify-content-center align-items-center">
                                        <a href="{{ route('admin.partners.edit', $partner) }}"
                                           class="btn btn-warning btn-sm w-100 w-sm-auto"
                                           data-bs-toggle="tooltip"
                                           title="Edit Partner">
                                            <i class="bi bi-pencil-square"></i>
                                            <span class="d-none d-sm-inline"> Edit</span>
                                        </a>

                                        <form action="{{ route('admin.partners.destroy', $partner) }}"
                                              method="POST"
                                              class="d-inline w-100 w-sm-auto">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm w-100 w-sm-auto"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete Partner"
                                                    onclick="return confirm('Are you sure you want to delete this partner?')">
                                                <i class="bi bi-trash"></i>
                                                <span class="d-none d-sm-inline"> Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-building fs-1 text-muted mb-2"></i>
                                        <p class="mb-0 text-muted">No partners found</p>
                                        <a href="{{ route('admin.partners.create') }}" class="btn btn-primary btn-sm mt-3">
                                            <i class="bi bi-plus-circle me-1"></i> Add First Partner
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
        @if($partners instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="card-footer bg-white py-2 py-sm-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <small class="text-muted order-2 order-sm-1">
                    Showing {{ $partners->firstItem() ?? 0 }} to {{ $partners->lastItem() ?? 0 }}
                    of {{ $partners->total() }} entries
                </small>
                <div class="order-1 order-sm-2 w-100 w-sm-auto">
                    {{ $partners->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.bg-purple {
    background-color: #9146FF !important;
    color: white !important;
}
</style>
@endsection