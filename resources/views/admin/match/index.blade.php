@extends('layouts.admin')

@section('content')
    @php
        $label = $tournament->format === 'solo' ? 'Player' : 'Team';
    @endphp

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">
                Match Bracket – {{ $tournament->title }}
            </h4>

            <div>
                @if (!$tournament->winner_team_id)
    <a href="{{ route('admin.match.create', $tournament->id) }}" class="btn btn-primary">
        + Create Match
    </a>
@else
    <button class="btn btn-secondary" disabled>
        Tournament Completed
    </button>
@endif

                <a href="{{ $tournament->format === 'solo'
                    ? route('admin.tournament-registrations.solo')
                    : route('admin.tournament-registrations.team') }}"
                    class="btn btn-outline-secondary ms-2">
                    ← Back
                </a>
            </div>
        </div>

        {{-- Tournament Winner --}}
        @if ($tournament->winner_team_id)
            <div class="alert alert-success text-center fw-bold">
                🏆 Tournament Winner:
                {{ $tournament->winner?->team_name ?? $tournament->winner?->name ?? 'N/A' }}
            </div>
        @endif

        {{-- Group matches by round --}}
        @foreach ($matches->groupBy('round') as $round => $roundMatches)
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light fw-bold">
                    {{ $round }}
                </div>

                <div class="card-body">

                    @foreach ($roundMatches as $match)
                        <form method="POST" action="{{ route('admin.match.winner', $match->id) }}">
                            @csrf

                            <div class="row align-items-center mb-3 border-bottom pb-3">

                                {{-- Team 1 --}}
                                <div class="col-md-4 fw-bold">
                                    {{ $match->team1->team_name ?? ($match->team1->name ?? '— TBD —') }} ({{ $match->team1->team_tag ?? "NA" }})
                                </div>

                                <div class="col-md-1 text-center fw-bold">VS</div>

                                {{-- Team 2 --}}
                                <div class="col-md-4 fw-bold">
                                    {{ $match->team2->team_name ?? ($match->team2->name ?? '— TBD —') }} ({{ $match->team2->team_tag ?? "NA" }})
                                </div>

                                {{-- Winner --}}
                                <div class="col-md-3">
                                    @if ($match->status === 'completed')
                                        <span class="badge bg-success">Winner Declared</span>
                                        <div class="fw-bold text-success mt-1">
                                            🏆 {{ $match->winner->team_name ?? $match->winner->name }}({{ $match->winner->team_tag ?? "NA"}})
                                        </div>
                                    @elseif ($match->team1_id || $match->team2_id)
                                        <select name="winner_id" class="form-select form-select-sm" required>
                                            <option value="">Select Winner</option>
                                            @if ($match->team1_id)
                                                <option value="{{ $match->team1_id }}">
                                                    {{ $match->team1->team_name ?? $match->team1->name }} ({{ $match->team1->team_tag ?? "NA"}})
                                                </option>
                                            @endif
                                            @if ($match->team2_id)
                                                <option value="{{ $match->team2_id }}">
                                                    {{ $match->team2->team_name ?? $match->team2->name }} ({{  $match->team2->team_tag ?? "NA"}})
                                                </option>
                                            @endif
                                        </select>
                                    @endif
                                </div>

                                {{-- Action --}}
                                <div class="col-md-12 mt-2">
                                    @if ($match->status === 'pending' && ($match->team1_id || $match->team2_id))
                                        <button class="btn btn-sm btn-primary">
                                            Save Result
                                        </button>
                                    @endif
                                </div>

                                {{-- MATCH DETAILS --}}
                                <div class="col-md-12 mt-3">
                                    @if ($match->match_date || $match->match_time || ($match->status === 'completed' && $match->match_video))
                                        <div class="alert alert-light border">
                                            <strong>Match Details:</strong><br>

                                            {{-- Date & Time always show --}}
                                            @if ($match->match_date)
                                                📅 {{ \Carbon\Carbon::parse($match->match_date)->format('d M Y') }}<br>
                                            @endif
                                            @if ($match->match_time)
                                                ⏰ {{ \Carbon\Carbon::parse($match->match_time)->format('H:i') }}<br>
                                            @endif

                                            {{-- Video only after match completed --}}
                                            @if ($match->status === 'completed' && $match->match_video)
                                                🎥 <a href="{{ $match->match_video }}" target="_blank">
                                                    Watch Match Video
                                                </a>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- Button to edit details --}}
                                    @if ($match->status === 'completed')
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#matchDetailsModal{{ $match->id }}">
                                            + Add / Edit Match Details
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>

                        {{-- MODAL for match details --}}
                        <div class="modal fade" id="matchDetailsModal{{ $match->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('admin.match.details', $match->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Match Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            {{-- Match Date --}}
                                            <div class="mb-3">
                                                <label class="form-label">Match Date</label>
                                                <input type="date" name="match_date" value="{{ $match->match_date }}"
                                                    class="form-control">
                                            </div>

                                            {{-- Match Time --}}
                                            <div class="mb-3">
                                                <label class="form-label">Match Time</label>
                                                <input type="time" name="match_time" value="{{ $match->match_time }}"
                                                    class="form-control">
                                            </div>

                                            {{-- Match Video (only relevant after match completion) --}}
                                            <div class="mb-3">
                                                <label class="form-label">Match Video Link</label>
                                                <input type="url" name="match_video" value="{{ $match->match_video }}"
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button class="btn btn-primary">Save Details</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection
