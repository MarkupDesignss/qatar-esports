@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-0 fw-bold">
                <i class="fas fa-gamepad me-2 text-primary"></i>Solo Registrations - {{ $tournament->title }}
            </h2>

        </div>
        <a href="{{ route('admin.tournament-registrations.solo') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Game:</strong> {{ $tournament->game->name ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Total Solo Players:</strong> <span
                            class="badge bg-primary">{{ $registrations->total() }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Player Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>User Account</th>
                            <th>Registered At</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $reg)
                        <tr>
                            <td>{{ $reg->id }}</td>
                            <td>{{ $reg->name }}</td>
                            <td>{{ $reg->email }}</td>
                            <td>{{ $reg->phone }}</td>
                            <td>{{ $reg->user->email ?? '-' }}</td>
                            <td>{{ $reg->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                {!! $reg->status ? '<span class="badge bg-success">Active</span>' :
                                '<span class="badge bg-danger">Inactive</span>' !!}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No registrations found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $registrations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection