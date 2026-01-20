<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsUserAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
//     public function newsList(Request $request)
// {
//     $userId = Auth::id(); // safer

//     $news = News::with('tournament')
//         ->when(
//             $request->type && $request->type !== 'all',
//             fn ($q) => $q->where('type', $request->type)
//         )
//         ->latest()
//         ->get()
//         ->map(function ($item) use ($userId) {

//             $action = NewsUserAction::where('news_id', $item->id)
//                 ->where('user_id', $userId)
//                 ->first();

//             return [
//                 'id' => $item->id,
//                 'title' => $item->title,
//                 'description' => $item->description,
//                 'thumbnail' => $item->thumbnail,
//                 'type' => $item->type,
//                 'tournament' => $item->tournament?->name,
//                 'like_count' => $item->like_count,
//                 'bookmark_count' => $item->bookmark_count,
//                 'is_liked' => $action?->is_liked ?? 0,
//                 'is_bookmarked' => $action?->is_bookmarked ?? 0,
//             ];
//         });

//     return response()->json([
//         'success' => true,
//         'count' => $news->count(),
//         'data' => $news
//     ]);
// }


// public function newsList(Request $request)
// {
//     // 🔐 Explicitly use sanctum guard
//     $userId = Auth::guard('sanctum')->id();

//     $news = News::with('tournament')
//         ->when(
//             $request->type && $request->type !== 'all',
//             fn ($q) => $q->where('type', $request->type)
//         )
//         ->latest()
//         ->get()
//         ->map(function ($item) use ($userId) {

//             $isLiked = 0;
//             $isBookmarked = 0;

//             if ($userId) {
//                 $action = NewsUserAction::where('news_id', $item->id)
//                     ->where('user_id', $userId)
//                     ->first();

//                 if ($action) {
//                     $isLiked = (int) $action->is_liked;
//                     $isBookmarked = (int) $action->is_bookmarked;
//                 }
//             }

//             return [
//                 'id' => $item->id,
//                 'title' => $item->title,
//                 'description' => $item->description,
//                 'thumbnail' => $item->thumbnail,
//                 'type' => $item->type,
//                 'tournament' => $item->tournament?->name,
//                 'like_count' => (int) $item->like_count,
//                 'bookmark_count' => (int) $item->bookmark_count,
//                 'is_liked' => $isLiked,
//                 'is_bookmarked' => $isBookmarked,
//                 'can_interact' => $userId ? 1 : 0,
//             ];
//         });

//     return response()->json([
//         'success' => true,
//         'user_id' => $userId, // helpful for debugging (can remove later)
//         'data' => $news
//     ]);
// }


public function newsList(Request $request)
{
    // 🔐 Explicitly use sanctum guard
    $userId = Auth::guard('sanctum')->id();

    // Optional: per_page parameter, default 10
    $perPage = $request->get('per_page', 10);

    // Build query
    $newsQuery = News::with('tournament')
        ->when(
            $request->type && $request->type !== 'all',
            fn ($q) => $q->where('type', $request->type)
        )
        ->latest();

    // Paginate results
    $newsPaginated = $newsQuery->paginate($perPage);

    // Transform paginated collection
    $newsPaginated->getCollection()->transform(function ($item) use ($userId) {

        $isLiked = 0;
        $isBookmarked = 0;

        if ($userId) {
            $action = NewsUserAction::where('news_id', $item->id)
                ->where('user_id', $userId)
                ->first();

            if ($action) {
                $isLiked = (int) $action->is_liked;
                $isBookmarked = (int) $action->is_bookmarked;
            }
        }

        return [
            'id' => $item->id,
            'title' => $item->title,
            'description' => $item->description,
            'thumbnail' => $item->thumbnail ? asset('storage/' . $item->thumbnail) : null,
            'type' => $item->type,
            'tournament' => $item->tournament?->name,
            'like_count' => (int) $item->like_count,
            'bookmark_count' => (int) $item->bookmark_count,
            'is_liked' => $isLiked,
            'is_bookmarked' => $isBookmarked,
            'can_interact' => $userId ? 1 : 0,
        ];
    });

    // Return paginated response
    return response()->json([
        'success' => true,
        'user_id' => $userId, // optional, for debugging
        'data' => $newsPaginated->items(),
        'pagination' => [
            'current_page' => $newsPaginated->currentPage(),
            'per_page' => $newsPaginated->perPage(),
            'total' => $newsPaginated->total(),
            'last_page' => $newsPaginated->lastPage(),
        ]
    ]);
}


    public function toggleLike($newsId)
{
    if (!Auth::check()) {
        return response()->json([
            'success' => false,
            'message' => 'Login required'
        ], 401);
    }

    $userId = Auth::id();

    $action = NewsUserAction::firstOrCreate([
        'user_id' => $userId,
        'news_id' => $newsId
    ]);

    $action->is_liked = !$action->is_liked;
    $action->save();

    $likeCount = NewsUserAction::where('news_id', $newsId)
        ->where('is_liked', 1)
        ->count();

    News::where('id', $newsId)->update([
        'like_count' => $likeCount
    ]);

    return response()->json([
        'success' => true,
        'is_liked' => (int) $action->is_liked,
        'like_count' => $likeCount
    ]);
}


   public function toggleBookmark($newsId)
{
    if (!Auth::check()) {
        return response()->json([
            'success' => false,
            'message' => 'Login required'
        ], 401);
    }

    $userId = Auth::id();

    $action = NewsUserAction::firstOrCreate([
        'user_id' => $userId,
        'news_id' => $newsId
    ]);

    $action->is_bookmarked = !$action->is_bookmarked;
    $action->save();

    $count = NewsUserAction::where('news_id', $newsId)
        ->where('is_bookmarked', 1)
        ->count();

    News::where('id', $newsId)->update([
        'bookmark_count' => $count
    ]);

    return response()->json([
        'success' => true,
        'is_bookmarked' => (int) $action->is_bookmarked,
        'bookmark_count' => $count
    ]);
}

}
