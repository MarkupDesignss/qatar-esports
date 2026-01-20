<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AboutSection;

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
}
