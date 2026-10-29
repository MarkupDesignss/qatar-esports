<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebPage;

class WebPageController extends Controller
{
    public function index()
    {
        $query = WebPage::orderBy('id', 'desc');
        $data['web_pages'] = $query->paginate(10)->withQueryString();
        return view('admin.web-pages.index', $data);
    }

    public function create()
    {
        return view('admin.web-pages.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'slug'        => 'required|string|max:255',
            'status'      => 'required|in:0,1', 
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:10240', // 10MB
        ]);

        $image = time().'.'.$request->image->extension();
        $request->image->storeAs('web_pages', $image, 'public');

        WebPage::create([
            'title'        => $request->title,
            'slug'         => $request->slug,
            'banner_image' => $image,
            'status'       => $request->status ?? 1
        ]);

        return redirect()->route('admin.web.page.list')->with('success','Web page Added Successfully!');
    }

    public function edit($id)
    {
        $web_page = WebPage::findOrFail($id);
        return view('admin.web-pages.edit',compact('web_page'));
    }

    public function update(Request $request, $id)
    {
        $web_page = WebPage::findOrFail($id);

        $request->validate([
            'title'  => 'required|string|max:255',
            'slug'   => 'required|string|max:255|unique:web_pages,slug,' . $id,
            'status' => 'required|in:0,1',
            'image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        if ($request->hasFile('image')) {

            // elete old image
            if (
                $web_page->banner_image &&
                \Storage::disk('public')->exists('web_pages/' . $web_page->banner_image)
            ) {
                \Storage::disk('public')->delete('web_pages/' . $web_page->banner_image);
            }

            // upload new image
            $image = time() . '.' . $request->image->extension();
            $request->image->storeAs('web_pages', $image, 'public');

            $web_page->banner_image = $image;
        }

        $web_page->update([
            'title'  => $request->title,
            'slug'   => $web_page->slug,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.web.page.list')
            ->with('success', 'Web Page Updated Successfully!');
    }

    public function destroy($id)
    {
        $web_page = WebPage::findOrFail($id);

        if ($web_page->banner_image && \Storage::disk('public')->exists('web_pages/'.$web_page->banner_image)) {
            \Storage::disk('public')->delete('web_pages/'.$web_page->banner_image);
        }

        $web_page->delete();

        return redirect()->route('admin.web.page.list')->with('success','Web Page Deleted Successfully!');
    }
      
}