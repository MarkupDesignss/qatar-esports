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
            Edit Match – {{ $tournament->title }}
        </h4>

        <a href="{{ route('admin.match.index', $tournament->id) }}"
           class="btn btn-outline-secondary">
            ← Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.match.update', [$tournament->id, $match->id]) }}"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                {{-- Round --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Round</label>
                    <select name="round" class="form-select" required>
                        @foreach (['Round 1', 'Quarterfinal', 'Semifinal', 'Final'] as $r)
                            <option value="{{ $r }}"
                                {{ $match->round == $r ? 'selected' : '' }}>
                                {{ $r }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Match Order --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Match Order</label>
                    <input type="number"
                           name="match_order"
                           class="form-control"
                           value="{{ old('match_order', $match->match_order) }}"
                           required>
                </div>

                {{-- Best Of --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Best Of</label>
                    <input type="number"
                           name="best_of"
                           class="form-control"
                           value="{{ old('best_of', $match->best_of) }}"
                           required>
                </div>

                {{-- Team 1 --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Team 1</label>
                    <select name="team1_id" id="team1" class="form-select">
                        <option value="">TBD</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}"
                                {{ $match->team1_id == $team->id ? 'selected' : '' }}>
                                {{ $team->team_name ?? $team->name }}
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
                            <option value="{{ $team->id }}"
                                {{ $match->team2_id == $team->id ? 'selected' : '' }}>
                                {{ $team->team_name ?? $team->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Date --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Match Date</label>
                    <input type="date"
                           name="match_date"
                           class="form-control"
                           value="{{ old('match_date', $match->match_date) }}">
                </div>

                {{-- Time --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Match Time</label>
                    <input type="time"
                           name="match_time"
                           class="form-control"
                           value="{{ old('match_time', $match->match_time) }}">
                </div>

                {{-- Banner --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Match Banner</label>
                    <input type="file" name="banner" class="form-control">

                    @if ($match->banner)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $match->banner) }}"
                                 width="120"
                                 class="rounded">
                        </div>
                    @endif
                </div>

                <button class="btn btn-primary">
                    Update Match
                </button>

            </form>

        </div>
    </div>
</div>

{{-- Prevent same team --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
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

    updateOptions();
});
</script>

@endsection