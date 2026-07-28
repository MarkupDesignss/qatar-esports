<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PageSetting;

class PageSettingApiController extends Controller
{
    public function show($slug)
    {
        $page = PageSetting::where('slug', $slug)->first();

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'slug'    => $page->slug,
                'title'   => $page->title,
                'content' => $page->content,
            ]
        ]);
    }
}