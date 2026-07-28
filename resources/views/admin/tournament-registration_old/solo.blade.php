@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col">
                <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">Solo Registrations</h4>
            </div>
             @if (hasPermission('participants.export'))
            <div class="col-auto">
                <a href="{{ route('admin.solo-registrations.export') }}" class="btn btn-success">
                    <i class="fas fa-download"></i> Export CSV
                </a>
            </div>
            @endif
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tournament</th>
                                <th>Game</th>
                                <th>Registered Users</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tournaments as $tournament)
                                <tr>
                                    <td><strong>{{ $tournament->title }}</strong></td>
                                    <td>{{ $tournament->game->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $tournament->registrations_count }}</span>
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="{{ route('admin.tournament-registrations.solo-detail', $tournament->id) }}"
                                            class="btn btn-sm btn-primary">View Details</a>
                                             @if (hasPermission('participants.approve'))
                                        @if ($tournament->format === 'solo')
                                            <!--<a href="{{ route('admin.match.index', $tournament->id) }}"-->
                                            <!--    class="btn btn-sm btn-success ms-1">-->
                                            <!--    Manage Matches-->
                                            <!--</a>-->
                                        @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No tournaments with solo registrations</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $tournaments->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection