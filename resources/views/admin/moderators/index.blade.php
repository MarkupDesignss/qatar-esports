@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-2 px-sm-3 px-md-4">

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2 gap-sm-0">
            <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">Moderators</h4>

            <a href="{{ route('admin.moderators.create') }}" class="btn btn-primary btn-sm btn-md-normal w-sm-auto">
                <i class="bi bi-plus-circle"></i> Add Moderator
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <span class="d-block">{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th width="70" class="text-center">Sr.No.</th>
                                <th>Name</th>
                                <th class="d-none d-md-table-cell">Email</th> <!-- Only on medium+ screens -->
                                <th width="180" class="text-center">Action</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($moderators as $key=>$moderator)
                                <tr>

                                    <td class="text-center">
                                        {{ $moderators->firstItem() + $key }}
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">{{ $moderator->name }}</span>
                                            <!-- Show email below name on small screens -->
                                            <span class="d-md-none text-muted small">{{ $moderator->email }}</span>
                                        </div>
                                    </td>

                                    <td class="d-none d-md-table-cell">
                                        {{ $moderator->email }}
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex flex-column flex-sm-row gap-1 gap-sm-1 justify-content-center align-items-center">
                                            <a href="{{ route('admin.moderators.edit', $moderator->id) }}"
                                                class="btn btn-warning btn-sm w-100 w-sm-auto">
                                                <i class="bi bi-pencil"></i>
                                                <span class="d-none d-sm-inline"> Edit</span>
                                            </a>

                                            <form action="{{ route('admin.moderators.destroy', $moderator->id) }}" method="POST"
                                                class="d-inline w-100 w-sm-auto">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm w-100 w-sm-auto"
                                                    onclick="return confirm('Delete this moderator?')">
                                                    <i class="bi bi-trash"></i>
                                                    <span class="d-none d-sm-inline"> Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bi bi-people fs-1 text-muted mb-2"></i>
                                            <p class="mb-0">No Moderators Found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="mt-3 d-flex justify-content-center justify-content-sm-end">
            <div class="w-100 w-sm-auto">
                {{ $moderators->links() }}
            </div>
        </div>

    </div>
@endsection