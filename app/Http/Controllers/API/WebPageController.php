<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebPage;
use Exception;

class WebPageController extends Controller
{
    // All web pages List
    public function list()
    {
        try {
            $web_pages = WebPage::where('status', 1)->get(['id','title','slug','banner_image','status']);

            // Map image to full URL
            $web_pages->transform(function($web_pages){
                $web_pages->banner_image = $web_pages->banner_image ? asset('storage/web_pages/'.$web_pages->banner_image) : null;
                return $web_pages;
            });

            return response()->json([
                'status' => true,
                'message' => 'Web pages list fetched successfully',
                'data' => $web_pages
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch web pages list',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
