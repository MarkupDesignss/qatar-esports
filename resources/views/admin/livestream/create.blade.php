@extends('layouts.admin')

@section('content')
<div class="container">
            @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h1 style="font-size: 1.7rem;font-weight:600;">Add Live Stream</h1>
    <form action="{{ route('admin.livestream.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Game</label>
            <select name="game_id" id="game_id" class="form-control">
                <option value="">Select Game</option>
                @foreach($games as $game)
                    <option value="{{ $game->id }}">{{ $game->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tournament</label>
            <select name="tournament_id" id="tournament_id" class="form-control">
                <option value="">Select Tournament</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Platform</label>
            <input type="text" name="platform" class="form-control">
        </div>

        <div class="mb-3">
            <label>Channel Name</label>
            <input type="text" name="channel_name" class="form-control">
        </div>

        <div class="mb-3">
            <label>Language</label>
            <input type="text" name="language" class="form-control">
        </div>

        <div class="mb-3">
            <label>Video URL</label>
            <input type="url" name="video_url" class="form-control">
        </div>

        <div class="mb-3">
            <label>Is Live?</label>
            <select name="is_live" class="form-control">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Viewer Count</label>
            <input type="number" name="viewer_count" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>

<script>
    document.getElementById('game_id').addEventListener('change', function() {
        let gameId = this.value;
        fetch('/admin/livestream/tournaments?game_id=' + gameId)
            .then(res => res.json())
            .then(data => {
                let tournamentSelect = document.getElementById('tournament_id');
                tournamentSelect.innerHTML = '<option value="">Select Tournament</option>';
                data.forEach(t => {
                    tournamentSelect.innerHTML += `<option value="${t.id}">${t.title}</option>`;
                });
            });
    });
</script>
@endsection
