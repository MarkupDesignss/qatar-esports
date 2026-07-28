{{-- Update your existing team-registrations.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <div class="card-header bg-white py-3">
            <div
                class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <div>
                    <h2 class="mb-0 h5 h4-sm fw-bold text-gray-800">
                        Team Registrations - {{ $tournament->title }}
                    </h2>
                </div>
                <div class="d-flex gap-2">
                    @if ($tournament->winner_team_id)
                        <span class="badge bg-warning p-2 d-flex align-items-center">
                            <i class="fas fa-trophy me-1"></i> Winner: {{ $tournament->winner_team_name }}
                        </span>
                        <!--<a href="{{ route('admin.team-registrations.prize-distribution', $tournament->id) }}"-->
                        <!--    class="btn btn-sm btn-success">-->
                        <!--    <i class="fas fa-money-bill-wave me-1"></i> Distribute Prize-->
                        <!--</a>-->
                    @endif
                    <a href="{{ route('admin.tournament-registrations.team') }}"
                        class="btn btn-sm btn-outline-secondary bg-secondary text-white w-sm-auto d-flex align-items-center justify-content-center">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
                                <th>Sr.No.</th>
                                <th>Team Name</th>
                                <th>Tag</th>
                                <th>Captain</th>
                                <th>Members</th>
                                <th>Team Logo</th>
                                <th>Invite Link</th>
                                <th>Registered At</th>
                                <th>Check in</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrations as $team)
                                <tr class="{{ $tournament->winner_team_id == $team->id ? 'table-success' : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $team->team_name }}
                                        @if ($tournament->winner_team_id == $team->id)
                                            <span class="badge bg-warning ms-1">
                                                <i class="fas fa-trophy"></i> Winner
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $team->team_tag }}</td>
                                    <td>{{ $team->name }}</td>
                                    <td>
                                        @php
                                            $count = \App\Models\TournamentRegistration::where(
                                                'invite_link',
                                                $team->invite_link,
                                            )->count();
                                        @endphp
                                        <span class="badge bg-info">{{ $count }}</span>
                                    </td>
                                    <td>
                                        @if ($team->team_logo)
                                            <img src="{{ asset('storage/' . $team->team_logo) }}"
                                                alt="{{ $team->team_name }}" style="max-width: 50px; height: auto;">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($team->invite_link)
                                            <a href="{{ url('?invite=' . $team->invite_link) }}" target="_blank"
                                                class="btn btn-sm btn-info">Link</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $team->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.team.toggle-status', $team->id) }}"
                                            onclick="return confirm('Are you sure you want to change status?')"
                                            style="text-decoration: none;">
                                            {!! $team->status
                                                ? '<span class="badge bg-success">Active</span>'
                                                : '<span class="badge bg-danger">Inactive</span>' !!}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <!-- View Members Button -->
                                            <a href="{{ route('admin.team-registrations.members', $team->id) }}"
                                                class="btn btn-sm btn-info" title="View Team Members">
                                                <i class="fas fa-users"></i>
                                            </a>

                                            <!-- Declare Winner Button -->
                                            @if (!$tournament->winner_team_id)
                                                <form
                                                    action="{{ route('admin.team-registrations.declare-winner', $tournament->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to declare {{ $team->team_name }} as the winner?');"
                                                    class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="winner_id" value="{{ $team->id }}">
                                                    <button type="submit" class="btn btn-sm btn-warning"
                                                        title="Declare Winner">
                                                        <i class="fas fa-trophy"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">No team registrations found</td>
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
