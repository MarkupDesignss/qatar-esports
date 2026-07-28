@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">Add Team Member</h4>
                    <small class="text-muted">
                        Tournament: {{ $tournament->title }} | Team: {{ $captain->team_name }}
                    </small>
                </div>
                <a href="{{ route('admin.team-registrations.members', $captain->id) }}" 
                   class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Members
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-user-plus me-2"></i>
                                Add Existing User to Team
                            </h5>
                            
                            <form action="{{ route('admin.team-registrations.add-member', $captain->id) }}" method="POST">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Select User <span class="text-danger">*</span></label>
                                    <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                        <option value="">-- Select a user --</option>
                                        @foreach($availableUsers as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->first_name }} {{ $user->last_name }} 
                                                ({{ $user->email }})
                                                @if($user->mobile)
                                                    - {{ $user->mobile }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Team Status:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Current Team Size: <strong>{{ $teamMembers->where('status', 1)->count() }}</strong> / {{ $tournament->team_size }}</li>
                                        <li>Available Users: <strong>{{ $availableUsers->count() }}</strong> users not registered in this tournament</li>
                                    </ul>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-user-plus me-1"></i> Add Member
                                    </button>
                                    <a href="{{ route('admin.team-registrations.members', $captain->id) }}" 
                                       class="btn btn-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Current Team Members Sidebar -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-users me-2"></i>
                                Current Team Members
                            </h5>
                            
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($teamMembers as $index => $member)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    {{ $member->name ?? $member->email }}
                                                    @if($member->is_captain)
                                                        <span class="badge bg-primary">Captain</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($member->is_captain)
                                                        <span class="badge bg-info">Captain</span>
                                                    @else
                                                        <span class="badge bg-secondary">Member</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($member->status)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No members found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-2">
                                <small class="text-muted">
                                    Total: {{ $teamMembers->count() }} members 
                                    (Active: {{ $teamMembers->where('status', 1)->count() }})
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection