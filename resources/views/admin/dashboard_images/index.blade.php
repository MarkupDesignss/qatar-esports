@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
        <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
            <i class="bi bi-images me-2"></i>Dashboard Images
        </h4>
        @if(empty($images))
        <a href="{{ route('admin.dashboard-images.create') }}" class="btn btn-primary btn-sm w-100 w-sm-auto">
            <i class="bi bi-plus-circle me-1"></i> Add Images
        </a>
        @endif
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

    @if($images)
        {{-- Images Display Card --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white py-2 py-sm-3">
                <h5 class="mb-0 fw-bold small">
                    <i class="bi bi-image me-2"></i>Dashboard Images
                </h5>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">Image 1</th>
                                <th class="text-center">Image 2</th>
                                <th class="text-center" width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <img src="{{ asset('storage/'.$images->image1) }}" 
                                             class="img-fluid rounded border"
                                             style="max-height: 150px; width: auto; max-width: 100%; object-fit: contain;"
                                             alt="Dashboard Image 1">
                                        <small class="text-muted mt-1">Image 1</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <img src="{{ asset('storage/'.$images->image2) }}" 
                                             class="img-fluid rounded border"
                                             style="max-height: 150px; width: auto; max-width: 100%; object-fit: contain;"
                                             alt="Dashboard Image 2">
                                        <small class="text-muted mt-1">Image 2</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column flex-sm-row gap-1 justify-content-center align-items-center">
                                        <a href="{{ route('admin.dashboard-images.edit', $images->id) }}" 
                                           class="btn btn-warning btn-sm w-100 w-sm-auto"
                                           data-bs-toggle="tooltip"
                                           title="Edit Images">
                                            <i class="bi bi-pencil-square"></i>
                                            <span class="d-none d-sm-inline"> Edit</span>
                                        </a>

                                        <form action="{{ route('admin.dashboard-images.destroy', $images->id) }}" 
                                              method="POST" 
                                              class="d-inline w-100 w-sm-auto">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm w-100 w-sm-auto"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete Images"
                                                    onclick="return confirm('Are you sure you want to delete these images?')">
                                                <i class="bi bi-trash"></i>
                                                <span class="d-none d-sm-inline"> Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>

    @else
        {{-- Empty State --}}
        <div class="card shadow-sm">
            <div class="card-body py-5">
                <div class="text-center">
                    <i class="bi bi-images fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted mb-3">No dashboard images found</h5>
                    <p class="text-muted small mb-3">Add images to display on the dashboard</p>
                    <a href="{{ route('admin.dashboard-images.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Add Images
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection