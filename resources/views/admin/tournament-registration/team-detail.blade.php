@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-0 fw-bold">
                <i class="fas fa-gamepad me-2 text-primary"></i>Team Registrations - {{ $tournament->title }}
            </h2>
        </div>
        <a href="{{ route('admin.tournament-registrations.team') }}" class="btn btn-outline-secondary">
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
                    <p><strong>Total Teams:</strong> <span class="badge bg-success">{{ $registrations->total() }}</span>
                    </p>
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
                            <th>Team Name</th>
                            <th>Tag</th>
                            <th>Captain</th>
                            <th>Members</th>
                            <th>Team Logo</th>
                            <th>Invite Link</th>
                            <th>Registered At</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $team)
                        <tr>
                            <td>{{ $team->id }}</td>
                            <td>{{ $team->team_name }}</td>
                            <td>{{ $team->team_tag }}</td>
                            <td>{{ $team->name }}</td>
                            <td>
                                @php
                                $count = \App\Models\TournamentRegistration::where('invite_link',
                                $team->invite_link)->count();
                                @endphp
                                <span class="badge bg-info">{{ $count }}</span>
                            </td>
                            <td>
                                @if($team->team_logo)
                                <img src="{{ asset('storage/' . $team->team_logo) }}" alt="{{ $team->team_name }}"
                                    style="max-width: 50px; height: auto;">
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                @if($team->invite_link)
                                <a href="{{ url('?invite=' . $team->invite_link) }}" target="_blank"
                                    class="btn btn-sm btn-info">Link</a>
                                @else
                                -
                                @endif
                            </td>
                            <td>{{ $team->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                {!! $team->status ? '<span class="badge bg-success">Active</span>' :
                                '<span class="badge bg-danger">Inactive</span>' !!}
                            </td>
                            @empty
                        <tr>
                            <td colspan="9" class="text-center">No team registrations found</td>
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