<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreviousWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PreviousWorkController extends Controller
{
    public function index()
    {
        $works = PreviousWork::latest()->get();
        return view('admin.previous-works.index', compact('works'));
    }

    public function create()
    {
        return view('admin.previous-works.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category'   => 'nullable|string|max:191',
            'title'      => 'nullable|string|max:191',
            'event_date' => 'nullable|date',
            'description' => 'nullable|string',
            'video_url'  => 'nullable|url',
            'image'      => 'required|image|max:2048',
            'status'     => 'required|boolean'
        ]);

        $data['image'] = $request->file('image')->store('previous-works', 'public');

        PreviousWork::create($data);

        return redirect()->route('admin.previous-works.index')
            ->with('success', 'Previous work added successfully');
    }

    public function edit(PreviousWork $previousWork)
    {
        return view('admin.previous-works.edit', compact('previousWork'));
    }

    public function update(Request $request, PreviousWork $previousWork)
    {
        $data = $request->validate([
            'category'   => 'nullable|string|max:191',
            'title'      => 'nullable|string|max:191',
            'event_date' => 'nullable|date',
            'description' => 'nullable|string',
            'video_url'  => 'nullable|url',
            'image'      => 'nullable|image|max:2048',
            'status'     => 'required|boolean'
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($previousWork->image);
            $data['image'] = $request->file('image')->store('previous-works', 'public');
        }

        $previousWork->update($data);

        return redirect()->route('admin.previous-works.index')
            ->with('success', 'Previous work updated successfully');
    }

    public function destroy(PreviousWork $previousWork)
    {
        Storage::disk('public')->delete($previousWork->image);
        $previousWork->delete();

        return back()->with('success', 'Deleted successfully');
    }
}
