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
    <h3 class="mb-3">Edit News</h3>

    <form action="{{ route('admin.news.update', $news->id) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Tournament</label>
            <select name="tournament_id" class="form-control">
                <option value="">-- Select Tournament --</option>
                @foreach($tournaments as $id => $name)
                    <option value="{{ $id }}"
                        {{ $news->tournament_id == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Title</label>
            <input type="text"
                   name="title"
                   class="form-control"
                   value="{{ old('title', $news->title) }}"
                   required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description"
                      rows="4"
                      class="form-control"
                      required>{{ old('description', $news->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-control" required>
                <option value="">-- Select Type --</option>

                <option value="all_news" {{ old('type', $news->type) == 'all_news' ? 'selected' : '' }}>
                    All
                </option>

                <option value="tournaments" {{ old('type', $news->type) == 'tournaments' ? 'selected' : '' }}>
                    Tournament
                </option>

                <option value="reports" {{ old('type', $news->type) == 'reports' ? 'selected' : '' }}>
                    Reports
                </option>

                <option value="team_stats" {{ old('type', $news->type) == 'team_stats' ? 'selected' : '' }}>
                    Team Stats
                </option>

                <option value="insights" {{ old('type', $news->type) == 'insights' ? 'selected' : '' }}>
                    Insights
                </option>

                <option value="mobile_esports" {{ old('type', $news->type) == 'mobile_esports' ? 'selected' : '' }}>
                    Mobile Esports
                </option>

                <option value="company_news" {{ old('type', $news->type) == 'company_news' ? 'selected' : '' }}>
                    Company News
                </option>
            </select>
        </div>


        @if($news->thumbnail)
            <div class="mb-3">
                <img src="{{ asset('storage/'.$news->thumbnail) }}" width="120">
            </div>
        @endif

        <div class="mb-3">
            <label>Change Thumbnail</label>
            <input type="file" name="thumbnail" class="form-control">
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
