<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsType;          // <-- new model
use App\Models\NewsUserAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    /**
     * Get all news types (for filter dropdown)
     */
    public function getTypes(Request $request)
    {
        $types = NewsType::where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'slug', 'name']);

        return response()->json([
            'success' => true,
            'data' => $types
        ]);
    }

    /**
     * List news with pagination, filter by type (dynamic)
     */
    public function newsList(Request $request)
    {
        $userId = Auth::guard('sanctum')->id();
        $perPage = $request->get('per_page', 10);

        $newsQuery = News::with(['tournament', 'type'])   // eager load type relation
            ->when($request->filled('type'), function ($q) use ($request) {
                // Accept either type_id or slug
                $typeParam = $request->type;
                if (is_numeric($typeParam)) {
                    $q->where('type_id', $typeParam);
                } else {
                    // find type by slug
                    $type = NewsType::where('slug', $typeParam)->first();
                    if ($type) {
                        $q->where('type_id', $type->id);
                    }
                }
            })
            ->latest();

        $newsPaginated = $newsQuery->paginate($perPage);

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
                'id'            => $item->id,
                'title'         => $item->title,
                'description'   => $item->description,
                'thumbnail'     => $item->thumbnail ? asset('storage/' . $item->thumbnail) : null,
                'type'          => $item->type ? [                 // return type object
                    'id'   => $item->type->id,
                    'slug' => $item->type->slug,
                    'name' => $item->type->name,
                ] : null,
                'tournament'    => $item->tournament?->name,
                'like_count'    => (int) $item->like_count,
                'bookmark_count'=> (int) $item->bookmark_count,
                'is_liked'      => $isLiked,
                'is_bookmarked' => $isBookmarked,
                'can_interact'  => $userId ? 1 : 0,
                'created_at'    => $item->created_at?->toISOString(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $newsPaginated->items(),
            'pagination' => [
                'current_page' => $newsPaginated->currentPage(),
                'per_page'     => $newsPaginated->perPage(),
                'total'        => $newsPaginated->total(),
                'last_page'    => $newsPaginated->lastPage(),
            ]
        ]);
    }

    // ... keep toggleLike, toggleBookmark, newsShow methods as they are, 
    // but update newsShow to return type object instead of string

    public function newsShow($id)
    {
        $userId = Auth::guard('sanctum')->id();
        $news = News::with(['tournament', 'type'])->find($id);

        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News not found'
            ], 404);
        }

        $isLiked = 0;
        $isBookmarked = 0;

        if ($userId) {
            $action = NewsUserAction::where('news_id', $news->id)
                ->where('user_id', $userId)
                ->first();
            if ($action) {
                $isLiked = (int) $action->is_liked;
                $isBookmarked = (int) $action->is_bookmarked;
            }
        }

        $data = [
            'id'            => $news->id,
            'title'         => $news->title,
            'description'   => $news->description,
            'content'       => $news->content ?? '',
            'thumbnail'     => $news->thumbnail ? asset('storage/' . $news->thumbnail) : null,
            'type'          => $news->type ? [
                'id'   => $news->type->id,
                'slug' => $news->type->slug,
                'name' => $news->type->name,
            ] : null,
            'tournament'    => $news->tournament?->name,
            'like_count'    => (int) $news->like_count,
            'bookmark_count'=> (int) $news->bookmark_count,
            'is_liked'      => $isLiked,
            'is_bookmarked' => $isBookmarked,
            'can_interact'  => $userId ? 1 : 0,
            'created_at'    => $news->created_at?->toISOString(),
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // toggleLike and toggleBookmark remain unchanged
}