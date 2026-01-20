<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use App\Models\ContactRequest;
use Illuminate\Http\Request;

class AboutSectionController extends Controller
{
    public function index()
    {
        $sections = AboutSection::orderBy('type')->get();
        return view('admin.about.index', compact('sections'));
    }

    public function create()
    {
        return view('admin.about.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:mission,vision,goals',
            'title' => 'required|string|max:191',
            'description' => 'required',
            'video_url' => 'nullable|url',
            'image' => 'nullable|image'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('about', 'public');
        }

        AboutSection::create($data);

        return redirect()->route('admin.about.index')->with('success', 'Section created');
    }

    public function edit($id)
    {
        $section = AboutSection::findOrFail($id);
        return view('admin.about.edit', compact('section'));
    }

    public function update(Request $request, $id)
    {
        $section = AboutSection::findOrFail($id);

        $data = $request->validate([
            'type' => 'required|in:mission,vision,goals',
            'title' => 'required|string|max:191',
            'description' => 'required',
            'video_url' => 'nullable|url',
            'status' => 'required|boolean'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('about', 'public');
        }

        $section->update($data);

        return redirect()->route('admin.about.index')->with('success', 'Section updated');
    }

    public function destroy($id)
    {
        AboutSection::findOrFail($id)->delete();
        return back()->with('success', 'Section deleted');
    }
    
        public function contact()
    {
        $contacts = ContactRequest::latest()->paginate(10);
        return view('admin.contact', compact('contacts'));
    }
    
        public function deleteContact($id)
    {
        try {
            $contact = ContactRequest::find($id);
            $contact->delete();

            return redirect()
                ->route('admin.contacts.index')
                ->with('success', 'Contact deleted successfully');
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong while deleting contact');
        }
    }
}
