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
use App\Models\News;
use App\Models\PreviousWork;
use App\Models\Partner;
use App\Models\Logo;
use Carbon\Carbon;
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
                'logo'    => $logos
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $th->getMessage()
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
                'error'   => $th->getMessage()
            ], 500);
        }
    }

    public function challenge()
    {
        try {
            $challenge = Challenge::select('welcome_heading', 'heading', 'content', 'image', 'video_url', 'thumbnail')->get();
            return response()->json([
                'success'   => true,
                'message'   => 'Matches fetched',
                'challenge' => $challenge
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $th->getMessage()
            ], 500);
        }
    }

    public function matchHighlights(Request $request)
    {
        try {
            $type = $request->get('type');

            // 1. If type = news, return only news
            if ($type === 'news') {
                $newsItems = News::select(
                    'id',
                    'title',
                    'description',
                    'thumbnail',
                    'created_at'
                )
                ->where('status', 1)
                ->latest()
                ->limit(10)
                ->get()
                ->map(function ($news) {
                    $news->type = 'news';
                    return $news;
                });

                return response()->json([
                    'success' => true,
                    'message' => 'News fetched',
                    'matches' => $newsItems
                ], 200);
            }

            // 2. Match highlights query
            $matchQuery = MatchHighlight::select(
                'id',
                'thumbnail',
                'title',
                'video_url',
                'video_title',
                'description',
                'created_at',
                'type'
            );

            // If specific match type is requested
            if ($request->filled('type') && $type !== 'all') {
                $matchQuery->where('type', $type);
            }

            $matches = $matchQuery->latest()->get();

            // 3. If specific match type is requested, return only matches
            if ($request->filled('type') && $type !== 'all') {
                return response()->json([
                    'success' => true,
                    'message' => 'Matches fetched',
                    'matches' => $matches
                ], 200);
            }

            // 4. News query for type = all / no type
            $newsItems = News::select(
                'id',
                'title',
                'description',
                'thumbnail',
                'created_at'
            )
            ->where('status', 1)
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($news) {
                $news->type = 'news';
                return $news;
            });

            // 5. Merge matches + news
            $mergedData = $matches->concat($newsItems)
                ->map(function ($item) {
                    $item->sort_order = ($item->type === 'news') ? 1 : 0;
                    return $item;
                })
                ->sortBy('sort_order')
                ->values()
                ->map(function ($item) {
                    unset($item->sort_order);
                    return $item;
                });

            return response()->json([
                'success' => true,
                'message' => 'Matches fetched',
                'matches' => $mergedData
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $th->getMessage()
            ], 500);
        }
    }

    public function apiMatchHighlightsShow($id)
    {
        $match = MatchHighlight::with(['images', 'contents'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $match
        ]);
    }

    public function welcomeSection()
    {
        return response()->json([
            'image'        => asset('welcome/image.jpg'),
            'imageContent' => 'Welcome to QEC',
            'videoUrl'     => 'https://youtube.com/xyz',
            'videoContent' => 'Watch our journey'
        ]);
    }

    public function featuredEvents()
    {
        try {
            $events = FeaturedEvent::select('title', 'description', 'image', 'image_second', 'status')->get();
            return response()->json([
                'success'  => true,
                'message'  => 'Events fetched',
                'partners' => $events
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $th->getMessage()
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
                'success'  => true,
                'message'  => 'Services fetched',
                'services' => $services
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $th->getMessage()
            ], 500);
        }
    }

    public function filterGames(Request $request)
    {
        $gameName = $request->query('game');

        $query = Game::query()->where('status', 1);

        if ($gameName) {
            $query->where('name', 'like', "%{$gameName}%");
        }

        $games = $query->select('id', 'name', 'slug', 'logo', 'banner', 'platform')->get();

        return response()->json($games);
    }

    public function featuredTournaments(Request $request)
    {
        $gameSlug = $request->query('game');
        $gameId   = $request->query('game_id');

        $query = Tournament::with('game:id,name,slug,logo')
            ->where('is_featured', true)
            ->where('visibility', 'published');

        if ($gameSlug) {
            $query->whereHas('game', function ($q) use ($gameSlug) {
                $q->where('slug', $gameSlug);
            });
        }

        if ($gameId) {
            $query->where('game_id', $gameId);
        }

        $tournaments = $query->get([
            'id',
            'title',
            'description',
            'banner',
            'slug',
            'entry_fee',
            'registration_start',
            'registration_end',
            'start_date',
            'end_date',
            'game_id'
        ]);

        return response()->json($tournaments);
    }

    public function eventsByPrize(Request $request)
    {
        $type = $request->query('type', 'upcoming');
        $now  = Carbon::now();

        $query = Tournament::select('id', 'title', 'prize_pool', 'start_date', 'end_date');

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
            'type'   => $type,
            'events' => $events
        ]);
    }

    public function previousWork()
    {
        try {
            $works = PreviousWork::select(
                'id',
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
                'data'    => $works
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $th->getMessage()
            ], 500);
        }
    }

    public function previousworkShow($id)
    {
        try {
            $work = PreviousWork::select(
                'category',
                'title',
                'event_date',
                'description',
                'image',
                'video_url',
                'status'
            )
            ->where('status', 1)
            ->find($id);

            if (!$work) {
                return response()->json([
                    'success' => false,
                    'message' => 'Previous work not found'
                ], 404);
            }

            if ($work->image) {
                $work->image = asset('storage/' . $work->image);
            }

            return response()->json([
                'success' => true,
                'message' => 'Previous work fetched successfully',
                'data'    => $work
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $th->getMessage()
            ], 500);
        }
    }

    public function partners()
    {
        try {
            $partners = Partner::select('name', 'logo', 'sort_order', 'status')->get();
            return response()->json([
                'success'  => true,
                'message'  => 'Partners fetched',
                'partners' => $partners
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $th->getMessage()
            ], 500);
        }
    }

    /* =========================================================
     |  POPULAR LIVE GAMES
     |  Live = started_at <= now()  (is_live column ignored)
     | ========================================================= */
    public function popularLiveGames()
    {
        $games = LiveStream::query()
            ->live()   // ✅ scope: started_at NOT NULL AND started_at <= now()

            // Tournament must be published (agar tournament_id null hai to wo bhi consider karne ke liye niche dekho)
            ->where(function ($q) {
                $q->whereHas('tournament', function ($t) {
                    $t->where('visibility', 'published');
                })
                ->orWhereNull('tournament_id');
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
                    'id'           => $row->game?->id,
                    'name'         => $row->game?->name,
                    'logo'         => $row->game?->logo,
                    'banner'       => $row->game?->banner,
                    'live_streams' => (int) $row->live_streams,
                    'viewer_count' => (int) $row->total_viewers,
                ];
            });

        return response()->json([
            'status' => true,
            'data'   => $games
        ]);
    }

    /* =========================================================
     |  POPULAR LIVE STREAMS
     |  Live = started_at <= now()  (is_live column ignored)
     | ========================================================= */
    public function popularLiveStreams(Request $request)
    {
        $gameId = $request->query('game_id'); // optional filter
    
        $streams = LiveStream::query()
            // ✅ Live + Upcoming dono
            ->whereNotNull('started_at')
    
            // Optional game filter
            ->when($gameId, function ($q) use ($gameId) {
                $q->where('game_id', $gameId);
            })
    
            // Related data
            ->with([
                'tournament:id,title,slug,status,timezone',
                'game:id,name'
            ])
    
            // Sort: Live pehle → phir upcoming (nearest first)
            ->orderByRaw('started_at <= ? DESC', [now()])
            ->orderByDesc('viewer_count')
            ->orderBy('started_at', 'asc')
            ->limit(20)
            ->get();
    
        return response()->json([
            'status' => true,
            'data'   => $streams->map(function ($stream) {
                $isLive = (bool) $stream->is_live; // model accessor se fresh value
    
                return [
                    'tournament' => $stream->tournament ? [
                        'title'  => $stream->tournament->title,
                        'slug'   => $stream->tournament->slug,
                        'status' => $stream->tournament->status,
                    ] : null,
    
                    'game' => $stream->game ? [
                        'id'   => $stream->game->id,
                        'name' => $stream->game->name,
                    ] : null,
    
                    'platform'           => $stream->platform,
                    'channel'            => $stream->channel_name,
                    'language'           => $stream->language,
                    'viewer_count'       => $stream->viewer_count,
                    'start_time'         => $stream->started_at?->toIso8601String(),
                    'start_time_display' => $stream->started_at?->format('d M Y, h:i A'),
                    'is_live'            => $isLive,
                    'starts_in_seconds'  => $isLive
                                                ? 0
                                                : (int) now()->diffInSeconds($stream->started_at, false),
                    'video_url'          => $stream->video_url,
                ];
            })
        ]);
    }
}