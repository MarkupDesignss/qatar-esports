@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2 py-sm-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Live Stream
                </h4>
                <a href="{{ route('admin.livestream.index') }}" class="btn btn-secondary btn-sm w-sm-auto">
                    <i class="bi bi-arrow-left me-1"></i> Back to Streams
                </a>
            </div>
        </div>

        <div class="card-body p-3 p-sm-4">
            <form action="{{ route('admin.livestream.update', $liveStream->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12 col-lg-8">

                        {{-- Game --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Game <span class="text-danger">*</span></label>
                            <select name="game_id" id="game_id" class="form-select form-select-lg @error('game_id') is-invalid @enderror" required>
                                <option value="">Select Game</option>
                                @foreach($games as $game)
                                    <option value="{{ $game->id }}" {{ old('game_id', $liveStream->game_id) == $game->id ? 'selected' : '' }}>
                                        {{ $game->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('game_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Tournament --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tournament</label>
                            <select name="tournament_id" id="tournament_id" class="form-select form-select-lg @error('tournament_id') is-invalid @enderror">
                                <option value="">Select Tournament</option>
                                @foreach($tournaments as $tournament)
                                    <option value="{{ $tournament->id }}" {{ old('tournament_id', $liveStream->tournament_id) == $tournament->id ? 'selected' : '' }}>
                                        {{ $tournament->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tournament_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Platform --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Platform <span class="text-danger">*</span></label>
                            <select name="platform" class="form-select form-select-lg @error('platform') is-invalid @enderror" required>
                                <option value="">Select Platform</option>
                                <option value="YouTube" {{ old('platform', $liveStream->platform) == 'YouTube' ? 'selected' : '' }}>YouTube</option>
                                <option value="Twitch" {{ old('platform', $liveStream->platform) == 'Twitch' ? 'selected' : '' }}>Twitch</option>
                                <option value="Facebook" {{ old('platform', $liveStream->platform) == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                                <option value="Kick" {{ old('platform', $liveStream->platform) == 'Kick' ? 'selected' : '' }}>Kick</option>
                                <option value="Other" {{ old('platform', $liveStream->platform) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('platform') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Channel Name --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Channel Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="channel_name" 
                                   class="form-control form-control-lg @error('channel_name') is-invalid @enderror" 
                                   value="{{ old('channel_name', $liveStream->channel_name) }}"
                                   placeholder="Enter channel name"
                                   required>
                            @error('channel_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Language --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Language <span class="text-danger">*</span></label>
                            <select name="language" class="form-select form-select-lg @error('language') is-invalid @enderror" required>
                                <option value="">Select Language</option>
                                <option value="English" {{ old('language', $liveStream->language) == 'English' ? 'selected' : '' }}>English</option>
                                <option value="Arabic" {{ old('language', $liveStream->language) == 'Arabic' ? 'selected' : '' }}>Arabic</option>
                                <option value="Spanish" {{ old('language', $liveStream->language) == 'Spanish' ? 'selected' : '' }}>Spanish</option>
                                <option value="French" {{ old('language', $liveStream->language) == 'French' ? 'selected' : '' }}>French</option>
                                <option value="German" {{ old('language', $liveStream->language) == 'German' ? 'selected' : '' }}>German</option>
                                <option value="Other" {{ old('language', $liveStream->language) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('language') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Video URL --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Video URL</label>
                            <input type="url" 
                                   name="video_url" 
                                   class="form-control form-control-lg @error('video_url') is-invalid @enderror" 
                                   value="{{ old('video_url', $liveStream->video_url) }}"
                                   placeholder="https://www.youtube.com/watch?v=...">
                            @error('video_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if($liveStream->video_url)
                                <div class="mt-2">
                                    <a href="{{ $liveStream->video_url }}" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-link-45deg"></i> Current URL
                                    </a>
                                </div>
                            @endif
                        </div>

                    </div>

                    {{-- Right Side --}}
                    <div class="col-12 col-lg-4">
                        <div class="card border shadow-sm">
                            <div class="card-body p-3 p-sm-4">
                                {{-- Is Live --}}
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select name="is_live" class="form-select">
                                        <option value="1" {{ old('is_live', $liveStream->is_live) ? 'selected' : '' }}>
                                            <i class="bi bi-broadcast"></i> Live Now
                                        </option>
                                        <option value="0" {{ old('is_live', $liveStream->is_live) ? '' : 'selected' }}>
                                            <i class="bi bi-clock"></i> Offline / Upcoming
                                        </option>
                                    </select>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-check-circle me-1"></i> Update Stream
                                    </button>
                                    <a href="{{ route('admin.livestream.index') }}" class="btn btn-outline-secondary ">
                                        <i class="bi bi-x-circle me-1"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- Dynamic Tournament Loading --}}
<script>
    document.getElementById('game_id').addEventListener('change', function() {
        let gameId = this.value;
        if (gameId) {
            fetch('/qatar-esports/admin/livestream/tournaments?game_id=' + gameId)
                .then(res => res.json())
                .then(data => {
                    let tournamentSelect = document.getElementById('tournament_id');
                    tournamentSelect.innerHTML = '<option value="">Select Tournament</option>';
                    data.forEach(t => {
                        tournamentSelect.innerHTML += `<option value="${t.id}">${t.title}</option>`;
                    });
                })
                .catch(err => {
                    console.error('Error loading tournaments:', err);
                });
        } else {
            let tournamentSelect = document.getElementById('tournament_id');
            tournamentSelect.innerHTML = '<option value="">Select Tournament</option>';
        }
    });
</script>
@endsection