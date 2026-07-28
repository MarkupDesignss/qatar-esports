<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AboutSection;
use App\Models\About;

class AboutSectionApiController extends Controller
{
    public function index(Request $request)
    {
        $query = AboutSection::where('status', 1);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $data = $query
            ->orderBy('id', 'desc')
            ->get([
                'id',
                'type',
                'title',
                'description',
                'video_url',
                'image'
            ]);

        return response()->json([
            'success' => true,
            'message' => 'About sections fetched successfully',
            'data' => $data
        ]);
    }

    public function mainAbout()
    {
        $about = About::first();
        return response()->json([
            'success' => true,
            'data' => $about ? [
                'heading'    => $about->heading,
                'description'=> $about->description,
                'badge'      => $about->badge,
                'image'      => $about->image ? asset('storage/'.$about->image) : null,
            ] : null,
        ]);
    }
}
