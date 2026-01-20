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
    <h3 class="mb-3">Create News</h3>

    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Tournament</label>
            <select name="tournament_id" class="form-control">
                <option value="">-- Select Tournament --</option>
                @foreach($tournaments as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Title</label>
            <input type="text"
                   name="title"
                   class="form-control"
                   value="{{ old('title') }}"
                   required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description"
                      rows="4"
                      class="form-control"
                      required>{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Type</label>
            'all_news','tournaments','reports','team_stats','insights','mobile_esports','company_news'
            <select name="type" class="form-control" required>
                <option value="">-- Select Type --</option>
                <option value="all_news">All</option>
                <option value="tournaments">Tournament</option>
                <option value="reports">Reports</option>
                <option value="team_stats">Team Stats</option>
                <option value="insights">Insights</option>
                <option value="mobile_esports">Mobile esports</option>
                <option value="company_news">Company name</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Thumbnail</label>
            <input type="file" name="thumbnail" class="form-control">
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
