<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use App\Models\ContactRequest;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Mail\ContactStatusUpdatedMail;
use Illuminate\Support\Facades\Mail;

class AboutSectionController extends Controller
{
    public function index()
    {
        $sections = AboutSection::orderBy('type')->get();
        $about = About::first();
        return view('admin.about.index', compact('sections', 'about'));
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
    
    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->ids;
            
            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No contacts selected for deletion.'
                ], 400);
            }
            
            // Delete the contacts
            ContactRequest::whereIn('id', $ids)->delete();
            
            return response()->json([
                'success' => true,
                'message' => count($ids) . ' contact(s) deleted successfully.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete contacts: ' . $e->getMessage()
            ], 500);
        }
    }
    
 
    // public function updateStatus(Request $request, ContactRequest $contact)
    // {
    //     $request->validate([
    //         'status' => 'required|in:new,in_progress,resolved',
    //         'resolution' => 'required_if:status,resolved|max:2000',
    //     ]);
    //     $allowedTransitions = [
    //         'new' => ['in_progress'],
    //         'in_progress' => ['resolved'],
    //         'resolved' => [],
    //     ];
    
    //     if (!in_array($request->status, $allowedTransitions[$contact->status])) {
    //         return back()->with('error', 'Invalid status transition.');
    //     }
    
    //     $contact->update([
    //         'status' => $request->status,
    //         'resolution' => $request->resolution,
    //     ]);
    
    //     Mail::to($contact->email)->send(new ContactStatusUpdatedMail($contact));
    
    //     return back()->with('success', 'Status updated successfully.');
    // }
    
        public function updateStatus(Request $request, $id)
    {
        try {
            $contact = ContactRequest::findOrFail($id);
            
            $request->validate([
                'status' => 'required|in:new,in_progress,resolved',
                'resolution' => 'required_if:status,resolved|max:2000',
            ]);

            $allowedTransitions = [
                'new' => ['in_progress'],
                'in_progress' => ['resolved'],
                'resolved' => [],
            ];

            if (!in_array($request->status, $allowedTransitions[$contact->status])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status transition.'
                ], 400);
            }

            $contact->update([
                'status' => $request->status,
                'resolution' => $request->status === 'resolved' ? $request->resolution : null,
            ]);

            // Send email notification
            Mail::to($contact->email)->send(new ContactStatusUpdatedMail($contact));

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.',
                'data' => [
                    'id' => $contact->id,
                    'status' => $contact->status,
                    'resolution' => $contact->resolution
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
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
    

    // Show edit form for main about
    public function editMain()
    {
        $about = About::first();
        if (!$about) {
            $about = About::create([
                'heading' => 'About Our Company',
                'description' => 'Write your description here...',
                'badge' => 'Since 2025',
                'image' => null,
            ]);
        }
        return view('admin.about.edit-main', compact('about'));
    }

    // Update main about
    public function updateMain(Request $request)
    {
        $about = About::first();

        $data = $request->validate([
            'heading' => 'required|string|max:255',
            'description' => 'required|string',
            'badge' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($about->image) {
                Storage::disk('public')->delete($about->image);
            }
            $data['image'] = $request->file('image')->store('about', 'public');
        }

        $about->update($data);

        return redirect()->route('admin.about.index')->with('success', 'Main About page updated!');
    }
}
