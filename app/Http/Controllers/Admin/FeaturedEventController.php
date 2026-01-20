<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeaturedEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeaturedEventController extends Controller
{
    public function index()
    {
        $events = FeaturedEvent::latest()->get();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'image'         => 'required|image',
            'image_second'  => 'required|image',
            'status'        => 'nullable|boolean',
        ]);

        // Upload images
        $data['image'] = $request->file('image')->store('events', 'public');
        $data['image_second'] = $request->file('image_second')->store('events', 'public');

        // Checkbox handling
        $data['status'] = $request->has('status');

        FeaturedEvent::create($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function edit(FeaturedEvent $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, FeaturedEvent $event)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'image'         => 'nullable|image',
            'image_second'  => 'nullable|image',
            'status'        => 'nullable|boolean',
        ]);

        // Replace first image if uploaded
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($event->image);
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        // Replace second image if uploaded
        if ($request->hasFile('image_second')) {
            Storage::disk('public')->delete($event->image_second);
            $data['image_second'] = $request->file('image_second')->store('events', 'public');
        }

        $data['status'] = $request->has('status');

        $event->update($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(FeaturedEvent $event)
    {
        // Delete both images
        Storage::disk('public')->delete([
            $event->image,
            $event->image_second,
        ]);

        $event->delete();

        return back()->with('success', 'Event deleted successfully.');
    }

    public function toggleStatus(FeaturedEvent $event)
    {
        $event->update([
            'status' => !$event->status,
        ]);

        return back();
    }
}
