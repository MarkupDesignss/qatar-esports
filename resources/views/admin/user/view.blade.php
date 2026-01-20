@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4 fw-bold">User Details</h4>

    {{-- BASIC USER INFO --}}
    <div class="card mb-4">
        <div class="card-header fw-bold">Basic Information</div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <td>{{ $user->id }}</td>
                </tr>

                <tr>
                    <th>Name</th>
                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                </tr>

                <tr>
                    <th>Username</th>
                    <td>{{ $user->username }}</td>
                </tr>

                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>

                <tr>
                    <th>Mobile</th>
                    <td>{{ $user->mobile }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }}">
                            {{ $user->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <th>Created At</th>
                    <td>{{ $user->created_at->format('d M Y h:i A') }}</td>
                </tr>

                <tr>
                    <th>Updated At</th>
                    <td>{{ $user->updated_at->format('d M Y h:i A') }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- TOURNAMENT REGISTRATIONS --}}
    <div class="card mb-4">
        <div class="card-header fw-bold">Tournament Registrations</div>
        <div class="card-body">
            @if($user->tournamentRegistrations->isEmpty())
                <p class="text-muted">This user has not registered for any tournaments.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tournament</th>
                            <th>Type</th>
                            <th>Team Name / Solo Name</th>
                            <th>Team Tag</th>
                            {{-- <th>Invite Link</th> --}}
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->tournamentRegistrations as $registration)
                            <tr>
                                <td>{{ $registration->tournament->title ?? 'N/A' }}</td>
                                <td>{{ ucfirst($registration->type) }}</td>
                                <td>
                                    @if($registration->type === 'solo')
                                        {{ $registration->name ?? '-' }}
                                    @else
                                        {{ $registration->team_name ?? '-' }}
                                    @endif
                                </td>
                                <td>{{ $registration->team_tag ?? '-' }}</td>
                                {{-- <td>{{ $registration->invite_link ?? '-' }}</td> --}}
                                <td>{{ $registration->created_at->format('d M Y h:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</div>
@endsection
