@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h4 class="page-title">Registration Details</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">{{ $registration->id }}</dd>

                <dt class="col-sm-3">Tournament</dt>
                <dd class="col-sm-9">{{ $registration->tournament->title ?? '-' }}</dd>

                <dt class="col-sm-3">Type</dt>
                <dd class="col-sm-9">{{ ucfirst($registration->type) }}</dd>

                <dt class="col-sm-3">Name</dt>
                <dd class="col-sm-9">{{ $registration->name }}</dd>

                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9">{{ $registration->email }}</dd>

                <dt class="col-sm-3">Phone</dt>
                <dd class="col-sm-9">{{ $registration->phone }}</dd>

                @if($registration->type === 'team')
                    <dt class="col-sm-3">Team Name</dt>
                    <dd class="col-sm-9">{{ $registration->team_name }}</dd>

                    <dt class="col-sm-3">Team Tag</dt>
                    <dd class="col-sm-9">{{ $registration->team_tag }}</dd>

                    <dt class="col-sm-3">Invite Link</dt>
                    <dd class="col-sm-9">{{ $registration->invite_link ?? '-' }}</dd>
                @endif

                <dt class="col-sm-3">Registered At</dt>
                <dd class="col-sm-9">{{ $registration->created_at->format('Y-m-d H:i') }}</dd>
            </dl>

            @if(!empty($members) && $members->count())
                <h5 class="mt-4">Team Members</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Is Captain</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $m)
                                <tr>
                                    <td>{{ $m->id }}</td>
                                    <td>{{ $m->name }}</td>
                                    <td>{{ $m->email }}</td>
                                    <td>{{ $m->phone }}</td>
                                    <td>{{ $m->is_captain ? 'Yes' : 'No' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="mt-3">
                <a href="{{ route('admin.tournament-registrations.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
