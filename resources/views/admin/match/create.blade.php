@extends('layouts.admin')

@section('content')
    <div class="container mt-4">

        {{-- Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">
                Create Match – {{ $tournament->title }}
            </h4>

            <a href="{{ route('admin.match.index', $tournament->id) }}" class="btn btn-outline-secondary">
                ← Back
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Round Selection --}}
                <form method="GET" action="{{ route('admin.match.create', $tournament->id) }}">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Round</label>
                        <select name="round" class="form-select" onchange="this.form.submit()" required>
                            <option value="">Select Round</option>
                            @foreach (['Round 1', 'Quarterfinal', 'Semifinal', 'Final'] as $r)
                                <option value="{{ $r }}" {{ request('round') == $r ? 'selected' : '' }}>
                                    {{ $r }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                {{-- Warning if not enough teams --}}
                @if (isset($teams) && $teams->count() < 2 && request('round'))
                    <div class="alert alert-warning">
                        ⚠️ Only {{ $teams->count() }} team(s) available for this round. You can still create the match
                        manually.
                    </div>
                @endif

                {{-- Create Match Form --}}
                <form method="POST" action="{{ route('admin.match.store', $tournament->id) }}" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="round" value="{{ request('round') }}">

                    {{-- Match Order --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Match Order</label>
                        <input type="number" name="match_order" class="form-control" min="1"
                            value="{{ old('match_order') }}" required>
                    </div>
                    {{-- Best Of --}}
                    <!--<div class="mb-3">-->
                    <!--    <label class="form-label fw-bold">Best Of</label>-->
                    <!--    <input type="number" name="best_of" class="form-control" min="1"-->
                    <!--        value="{{ old('best_of') }}" required>-->
                    <!--</div>-->

                    {{-- Team 1 --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Team 1</label>
                        <select name="team1_id" id="team1" class="form-select">
                            <option value="">TBD</option>
                            @foreach ($teams as $team)
                                <option value="{{ $team->id }}" {{ old('team1_id') == $team->id ? 'selected' : '' }}>
                                    {{ $team->team_name ?? $team->name }} ({{ $team->team_tag ?? "NA"}})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Team 2 --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Team 2</label>
                        <select name="team2_id" id="team2" class="form-select">
                            <option value="">TBD</option>
                            @foreach ($teams as $team)
                                <option value="{{ $team->id }}" {{ old('team2_id') == $team->id ? 'selected' : '' }}>
                                    {{ $team->team_name ?? $team->name }} ({{ $team->team_tag ?? "NA" }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Match Date --}}
{{-- Match Date --}}
<div class="mb-3">
    <label for="match_date" class="form-label fw-bold">Match Date</label>
    <input
        type="date"
        id="match_date"
        name="match_date"
        class="form-control"
        value="{{ old('match_date') }}"
        onclick="this.showPicker(); event.stopPropagation();">
</div>

{{-- Match Time --}}
<div class="mb-3">
    <label for="match_time" class="form-label fw-bold">Match Time</label>
    <input
        type="time"
        id="match_time"
        name="match_time"
        class="form-control"
        value="{{ old('match_time') }}"
        onclick="this.showPicker(); event.stopPropagation();">
</div>



                    
                      {{-- Match Banner --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Match Banner
                        </label>
                        <input type="file" name="banner" class="form-control" accept="image/*">
                    </div>
                    
                    @if(now()->between($tournament->registration_start, $tournament->registration_end))
    <button class="btn btn-secondary" disabled>
        Registration Ongoing
    </button>
@else
    <button class="btn btn-primary">Create Match</button>
@endif

                    
                </form>

            </div>
        </div>
    </div>

    {{-- Prevent same team selection --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const team1 = document.getElementById('team1');
            const team2 = document.getElementById('team2');

            function updateOptions() {
                [...team1.options, ...team2.options].forEach(o => o.disabled = false);

                if (team1.value) {
                    [...team2.options].forEach(o => {
                        if (o.value === team1.value) o.disabled = true;
                    });
                }

                if (team2.value) {
                    [...team1.options].forEach(o => {
                        if (o.value === team2.value) o.disabled = true;
                    });
                }
            }

            team1.addEventListener('change', updateOptions);
            team2.addEventListener('change', updateOptions);

            // Initialize on load in case old values are set
            updateOptions();
        });
    </script>
@endsection
