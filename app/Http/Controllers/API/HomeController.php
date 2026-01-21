<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Event;
use App\Models\Service;
use App\Models\Game;
use App\Models\Tournament;
use App\Models\Challenge;
use App\Models\FeaturedEvent;
use App\Models\LiveStream;
use App\Models\MatchHighlight;
use App\Models\PreviousWork;
use App\Models\Partner;
 use Carbon\Carbon;
 use App\Models\Logo;
use Illuminate\Support\Facades\URL;


class HomeController extends Controller
{

public function logo()
{
    try {
        $logos = Logo::all()->map(function ($logo) {
            $logo->image = URL::to('storage/' . $logo->image);
            return $logo;
        });

        return response()->json([
            'success' => true,
            'message' => 'Logo fetched',
            'logo' => $logos
        ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong',
            'error' => $th->getMessage()
        ], 500);
    }
}


public function banners()
{
    try {
        $banners = Banner::select('image', 'heading', 'description')
            ->get()
            ->map(function ($banner) {
                $banner->image = URL::to('storage/' . $banner->image);
                return $banner;
            });

        return response()->json([
            'success' => true,
            'message' => 'Banners fetched',
            'banners' => $banners
        ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong',
            'error' => $th->getMessage()
        ], 500);
    }
}

        public function challenge()
    {
        try {
            $challenge = Challenge::select('heading', 'content', 'image', 'video_url','thumbnail')->get();
            return response()->json([
                'success' => true,
                'message' => 'Matches fetched',
                'challenge' => $challenge
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function matchHighlights(Request $request)
{
    try {

        $query = MatchHighlight::select(
            'thumbnail',
            'title',
            'video_url',
            'video_title',
            'description',
            'created_at',
            'type'
        );

        /*
        |-------------------------------------------------
        | Filter by Type
        |-------------------------------------------------
        */
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        $matches = $query->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Matches fetched',
            'matches' => $matches
        ], 200);

    } catch (\Throwable $th) {

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong',
            'error' => $th->getMessage()
        ], 500);
    }
}


    public function apiMatchHighlightsShow($id)
    {
        $match = MatchHighlight::with(['images', 'contents'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $match
        ]);
    }

    public function welcomeSection()
    {
        return response()->json([
            'image' => asset('welcome/image.jpg'),
            'imageContent' => 'Welcome to QEC',
            'videoUrl' => 'https://youtube.com/xyz',
            'videoContent' => 'Watch our journey'
        ]);
    }

    public function featuredEvents()
    {
        try {
            $events = FeaturedEvent::select('title', 'description', 'image','image_second', 'status')->get();
            return response()->json([
                'success' => true,
                'message' => 'Events fetched',
                'partners' => $events
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 500);
        }
    }


public function services()
{
    try {
        $services = Service::select(
                'title',
                'description',
                'image',
                'button_text',
                'button_link'
            )
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Services fetched',
            'services' => $services
        ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong',
            'error' => $th->getMessage()
        ], 500);
    }
}

    public function filterGames(Request $request)
    {
        $gameName = $request->query('game');

        $query = Game::query()->where('status', 1);

        if($gameName) {
            $query->where('name', 'like', "%{$gameName}%");
        }

        $games = $query->select('id', 'name', 'slug', 'logo', 'banner', 'platform')->get();

        return response()->json($games);
    }

     public function featuredTournaments(Request $request)
    {
        $gameSlug = $request->query('game');     // optional
        $gameId   = $request->query('game_id');

        $query = Tournament::with('game:id,name,slug,logo')
            ->where('is_featured', true);

        // Filter by game slug
        if ($gameSlug) {
            $query->whereHas('game', function ($q) use ($gameSlug) {
                $q->where('slug', $gameSlug);
            });
        }

        // Filter by game id
        if ($gameId) {
            $query->where('game_id', $gameId);
        }

        $tournaments = $query->get([
            'id',
            'title',
            'slug',
            'entry_fee',
            'registration_start',
            'game_id'
        ]);

        return response()->json($tournaments);
    }

    public function eventsByPrize(Request $request)
    {
        $type = $request->query('type', 'upcoming');
        $now = Carbon::now();

        $query = Tournament::select('id','title','prize_pool','start_date','end_date');

        // Dynamic status filter
        if ($type == 'upcoming') {
            $query->where('start_date', '>', $now);
        } elseif ($type == 'live') {
            $query->where('start_date', '<=', $now)
                ->where('end_date', '>=', $now);
        } elseif ($type == 'completed') {
            $query->where('end_date', '<', $now);
        } else {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        $events = $query->get();

        return response()->json([
            'type' => $type,
            'events' => $events
        ]);
    }



    public function previousWork()
    {
        try {
            $works = PreviousWork::select(
                'category',
                'title',
                'event_date',
                'description',
                'image',
                'video_url',
                'status'
            )
                ->where('status', 1)
                ->orderBy('event_date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Previous works fetched successfully',
                'data' => $works
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function partners()
    {
        try {
            $partners = Partner::select('name', 'logo', 'sort_order', 'status')->get();
            return response()->json([
                'success' => true,
                'message' => 'Partners fetched',
                'partners' => $partners
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    //Popular Live Games API
    // public function popularLiveGames()
    // {
    //     $now = Carbon::now()->format('Y-m-d H:i:s');
    //     $today = Carbon::today()->format('Y-m-d');

    //     $games = Game::select('id', 'name', 'logo')
    //         ->withCount([
    //             'tournaments as live_count' => function ($q) use ($now, $today) {
    //                 $q->whereRaw(
    //                     "CONCAT(start_date, ' ', start_time) <= ?",
    //                     [$now]
    //                 )
    //                 ->where('end_date', '>=', $today);
    //             }
    //         ])
    //         ->orderByDesc('live_count')
    //         ->get();

    //     return response()->json(['data'=>$games]);
    // }

    public function popularLiveGames()
    {
        $games = LiveStream::query()
            ->where('is_live', 1)

            // Tournament must be published
            ->whereHas('tournament', function ($q) {
                $q->where('visibility', 'published');
            })

            ->selectRaw('
                game_id,
                COUNT(id) as live_streams,
                SUM(viewer_count) as total_viewers
            ')
            ->groupBy('game_id')
            ->orderByDesc('total_viewers')

            ->with('game:id,name,logo,banner')

            ->get()
            ->map(function ($row) {
                return [
                    'id'            => $row->game?->id,
                    'name'          => $row->game?->name,
                    'logo'          => $row->game?->logo,
                    'banner'          => $row->game?->banner,
                    'live_streams'  => (int) $row->live_streams,
                    'viewer_count'  => (int) $row->total_viewers,
                ];
            });

        return response()->json([
            'status' => true,
            'data'   => $games
        ]);
    }


    //Popular Live Streams API
    public function popularLiveStreams(Request $request)
    {
        $gameId = $request->query('game_id'); // optional filter

        $streams = LiveStream::query()
            ->where('is_live', true)

            // Optional game filter
            ->when($gameId, function ($q) use ($gameId) {
                $q->where('game_id', $gameId);
            })

            // Include related data
            ->with([
                'tournament:id,title,slug,status,timezone',
                'game:id,name'
            ])
            ->orderByDesc('viewer_count')
            ->limit(20)
            ->get();

        return response()->json([
            'status' => true,
            'data' => $streams->map(function ($stream) {
                return [
                    'tournament' => [
                        'title'  => $stream->tournament->title,
                        'slug'   => $stream->tournament->slug,
                        'status' => $stream->tournament->status,
                    ],
                    'game' => $stream->game ? [
                        'id'   => $stream->game->id,
                        'name' => $stream->game->name,
                    ] : null,
                    'platform'     => $stream->platform,
                    'channel'      => $stream->channel_name,
                    'language'     => $stream->language,
                    'viewer_count' => $stream->viewer_count,
                    'is_live'      => (bool) $stream->is_live,
                    'video_url'    => $stream->video_url,
                ];
            })
        ]);
    }


}