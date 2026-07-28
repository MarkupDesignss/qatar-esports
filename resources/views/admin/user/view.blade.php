@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
        <i class="bi bi-person-circle me-2"></i>User Details
    </h4>

    <a href="{{ route('admin.user.index') }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

    {{-- BASIC USER INFO --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white py-2 py-sm-3">
            <h5 class="mb-0 fw-bold small">
                <i class="bi bi-info-circle me-2"></i>Basic Information
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th class="bg-light" style="width: 30%; min-width: 120px;">ID</th>
                            <td>{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Name</th>
                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Username</th>
                            <td>{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Mobile</th>
                            <td>{{ $user->mobile }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Status</th>
                            <td>
                                <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                                    {{ $user->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">Created At</th>
                            <td>{{ $user->created_at->format('d M Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Updated At</th>
                            <td>{{ $user->updated_at->format('d M Y h:i A') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- PROFILE & SOCIAL LINKS --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white py-2 py-sm-3">
            <h5 class="mb-0 fw-bold small">
                <i class="bi bi-person-badge me-2"></i>Profile & Social Links
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <tbody>
                        {{-- Profile Image --}}
                        <tr>
                            <th class="bg-light" style="width: 30%; min-width: 120px;">Profile Image</th>
                            <td>
                                @if($user->profile && $user->profile->profile_image)
                                    <img src="{{ asset('storage/' . $user->profile->profile_image) }}" 
                                         alt="Profile Image" 
                                         class="img-fluid rounded" 
                                         style="max-width: 150px; max-height: 150px;">
                                @else
                                    <span class="text-muted">
                                        <i class="bi bi-image me-1"></i>Not uploaded
                                    </span>
                                @endif
                            </td>
                        </tr>

                        {{-- ID Proof --}}
                        <tr>
                            <th class="bg-light">ID Proof</th>
                            <td>
                                @if($user->profile && $user->profile->id_proof)
                                    <a href="{{ asset('storage/' . $user->profile->id_proof) }}" 
                                       target="_blank" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-file-earmark-pdf me-1"></i>View ID Proof
                                    </a>
                                @else
                                    <span class="text-muted">
                                        <i class="bi bi-file-earmark-x me-1"></i>Not uploaded
                                    </span>
                                @endif
                            </td>
                        </tr>

                        {{-- Social Links --}}
                        <tr>
                            <th class="bg-light"><i class="bi bi-facebook me-2"></i>Facebook</th>
                            <td>
                                @if($user->socialLinks && $user->socialLinks->facebook)
                                    <a href="{{ $user->socialLinks->facebook }}" target="_blank" class="text-decoration-none">
                                        {{ $user->socialLinks->facebook }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light"><i class="bi bi-instagram me-2"></i>Instagram</th>
                            <td>
                                @if($user->socialLinks && $user->socialLinks->instagram)
                                    <a href="{{ $user->socialLinks->instagram }}" target="_blank" class="text-decoration-none">
                                        {{ $user->socialLinks->instagram }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light"><i class="bi bi-twitter me-2"></i>Twitter</th>
                            <td>
                                @if($user->socialLinks && $user->socialLinks->twitter)
                                    <a href="{{ $user->socialLinks->twitter }}" target="_blank" class="text-decoration-none">
                                        {{ $user->socialLinks->twitter }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light"><i class="bi bi-youtube me-2"></i>YouTube</th>
                            <td>
                                @if($user->socialLinks && $user->socialLinks->youtube)
                                    <a href="{{ $user->socialLinks->youtube }}" target="_blank" class="text-decoration-none">
                                        {{ $user->socialLinks->youtube }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light"><i class="bi bi-discord me-2"></i>Discord</th>
                            <td>
                                @if($user->socialLinks && $user->socialLinks->discord)
                                    <a href="{{ $user->socialLinks->discord }}" target="_blank" class="text-decoration-none">
                                        {{ $user->socialLinks->discord }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light"><i class="bi bi-twitch me-2"></i>Twitch</th>
                            <td>
                                @if($user->socialLinks && $user->socialLinks->twitch)
                                    <a href="{{ $user->socialLinks->twitch }}" target="_blank" class="text-decoration-none">
                                        {{ $user->socialLinks->twitch }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TOURNAMENT REGISTRATIONS --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white py-2 py-sm-3">
            <h5 class="mb-0 fw-bold small">
                <i class="bi bi-trophy me-2"></i>Tournament Registrations
                <span class="badge bg-primary ms-2">{{ $user->tournamentRegistrations->count() }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            @if($user->tournamentRegistrations->isEmpty())
                <div class="text-center py-4">
                    <i class="bi bi-trophy fs-1 text-muted d-block mb-2"></i>
                    <p class="text-muted mb-0">This user has not registered for any tournaments.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Tournament</th>
                                <th class="d-none d-sm-table-cell">Type</th>
                                <th>Team / Solo Name</th>
                                <th class="d-none d-md-table-cell">Team Tag</th>
                                <th class="text-end">Registered At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->tournamentRegistrations as $registration)
                                <tr>
                                    <td>
                                        <strong>{{ $registration->tournament->title ?? 'N/A' }}</strong>
                                        <div class="d-sm-none text-muted small">
                                            Type: {{ ucfirst($registration->type) }}
                                        </div>
                                    </td>
                                    <td class="d-none d-sm-table-cell">
                                        <span class="badge {{ $registration->type === 'solo' ? 'bg-info' : 'bg-success' }}">
                                            {{ ucfirst($registration->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($registration->type === 'solo')
                                            {{ $registration->name ?? '-' }}
                                        @else
                                            {{ $registration->team_name ?? '-' }}
                                        @endif
                                        <div class="d-md-none text-muted small">
                                            Tag: {{ $registration->team_tag ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell">{{ $registration->team_tag ?? '-' }}</td>
                                    <td class="text-end small">{{ $registration->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="d-flex flex-column flex-sm-row gap-2 gap-sm-3">
       
        <a href="{{ route('admin.user.index') }}" class="btn btn-secondary w-sm-auto">
            <i class="bi bi-arrow-left"></i> Back to Users
        </a>
    </div>

</div>
@endsection

<style>
/* Responsive table styling */
.table th {
    white-space: nowrap;
}

@media (max-width: 576px) {
    .table th {
        font-size: 12px;
        padding: 8px;
    }
    .table td {
        font-size: 12px;
        padding: 8px;
        word-break: break-word;
    }
    .badge {
        font-size: 10px;
    }
}

/* Social link truncation on mobile */
@media (max-width: 576px) {
    .table td a {
        display: block;
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
}
</style>