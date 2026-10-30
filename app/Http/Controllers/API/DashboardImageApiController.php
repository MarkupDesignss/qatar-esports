<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DashboardImage;
use App\Models\TournamentRegistration;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardImageApiController extends Controller
{
    public function index()
    {
        $images = DashboardImage::first();

        if (!$images) {
            return response()->json([
                'success' => false,
                'message' => 'No images found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'image1' => $images->image1 ? asset('storage/' . $images->image1) : null,
                'image2' => $images->image2 ? asset('storage/' . $images->image2) : null,
            ]
        ]);
    }
    


public function userRegistrationRanking(Request $request)
{
    $type = $request->query('type'); // solo | team | null
    $baseUrl = 'http://13.63.124.252/storage';

    $query = TournamentRegistration::query()
        ->select([
            'tournament_registrations.user_id',

            // total registrations
            DB::raw('COUNT(tournament_registrations.id) as total_registrations'),

            // total winnings
            DB::raw('COALESCE(SUM(
                CASE 
                    WHEN tournaments.winner_team_id = tournament_registrations.id 
                    THEN tournaments.prize_pool 
                    ELSE 0 
                END
            ), 0) as total_winnings'),

            // user profile image (FULL URL)
            DB::raw("
                MAX(
                    CASE 
                        WHEN user_profiles.profile_image IS NOT NULL 
                        THEN CONCAT('$baseUrl/', user_profiles.profile_image)
                        ELSE NULL
                    END
                ) as profile_image
            ")
        ])
        ->leftJoin(
            'tournaments',
            'tournaments.winner_team_id',
            '=',
            'tournament_registrations.id'
        )
        ->leftJoin(
            'user_profiles',
            'user_profiles.user_id',
            '=',
            'tournament_registrations.user_id'
        )
        ->groupBy('tournament_registrations.user_id')
        ->orderByDesc('total_registrations');

    if ($type) {
        $query->where('tournament_registrations.type', $type);
    }

    $ranking = $query
        ->with('user:id,first_name,last_name,username,email')
        ->get();

    return response()->json([
        'success' => true,
        'data' => $ranking
    ]);
}


// public function tournamentPrizeDistribution(Request $request)
// {
//     $filter = $request->query('filter', 'week'); // week | month | year

//     /**
//      * =========================
//      * WEEK → Mon–Sun (DEFAULT)
//      * =========================
//      */
//     if ($filter === 'week') {

//         $data = DB::table('tournaments')
//             ->select(
//                 DB::raw('WEEKDAY(end_date) as day_no'), // 0=Mon
//                 DB::raw('DAYNAME(end_date) as day'),
//                 DB::raw('SUM(prize_pool) as total')
//             )
//             ->whereNotNull('winner_team_id')
//             ->whereBetween('end_date', [
//                 now()->startOfWeek(),
//                 now()->endOfWeek()
//             ])
//             ->groupBy('day_no', 'day')
//             ->orderBy('day_no')
//             ->get();

//         $days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
//         $map = [];

//         foreach ($data as $row) {
//             $map[substr($row->day, 0, 3)] = (float) $row->total;
//         }

//         return response()->json([
//             'success' => true,
//             'labels'  => $days,
//             'values'  => array_map(fn ($d) => $map[$d] ?? 0, $days)
//         ]);
//     }

//     /**
//      * =========================
//      * MONTH → Jan–Dec (CURRENT YEAR)
//      * =========================
//      */
//     if ($filter === 'month') {

//         $data = DB::table('tournaments')
//             ->select(
//                 DB::raw('MONTH(end_date) as month_no'),
//                 DB::raw('MONTHNAME(end_date) as month'),
//                 DB::raw('SUM(prize_pool) as total')
//             )
//             ->whereNotNull('winner_team_id')
//             ->whereYear('end_date', now()->year)
//             ->groupBy('month_no', 'month')
//             ->orderBy('month_no')
//             ->get();

//         $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
//         $map = [];

//         foreach ($data as $row) {
//             $map[substr($row->month, 0, 3)] = (float) $row->total;
//         }

//         return response()->json([
//             'success' => true,
//             'labels'  => $months,
//             'values'  => array_map(fn ($m) => $map[$m] ?? 0, $months)
//         ]);
//     }

//     /**
//      * =========================
//      * YEAR → Year-wise totals
//      * =========================
//      */
//     if ($filter === 'year') {

//         $data = DB::table('tournaments')
//             ->select(
//                 DB::raw('YEAR(end_date) as year'),
//                 DB::raw('SUM(prize_pool) as total')
//             )
//             ->whereNotNull('winner_team_id')
//             ->groupBy('year')
//             ->orderBy('year')
//             ->get();

//         return response()->json([
//             'success' => true,
//             'labels'  => $data->pluck('year'),
//             'values'  => $data->pluck('total')->map(fn ($v) => (float) $v)
//         ]);
//     }

//     return response()->json([
//         'success' => false,
//         'message' => 'Invalid filter'
//     ], 400);
// }

   public function tournamentPrizeDistribution(Request $request)
    {
        try {
            // 1. Authenticate user
            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }
    
            $userId = $user->id;
            $filter = $request->query('filter', 'week'); // week | month | year
    
            /**
             * Base query: Only this user's registrations where prize_amount > 0
             * and tournament is completed
             */
            $baseQuery = DB::table('tournament_registrations as tr')
                ->join('tournaments as t', 't.id', '=', 'tr.tournament_id')
                ->where('tr.user_id', $userId)
                ->whereNotNull('tr.prize_amount')
                ->where('tr.prize_amount', '>', 0)
                ->where('t.end_date', '<', now());
    
            /**
             * =========================
             * WEEK → Mon–Sun (DEFAULT)
             * =========================
             */
            if ($filter === 'week') {
                $start = now()->startOfWeek(Carbon::MONDAY);
                $end   = now()->endOfWeek(Carbon::SUNDAY);
    
                $data = (clone $baseQuery)
                    ->select(
                        DB::raw('WEEKDAY(t.end_date) as day_no'),
                        DB::raw("DATE_FORMAT(t.end_date, '%a') as day"),
                        DB::raw('COALESCE(SUM(tr.prize_amount), 0) as total')
                    )
                    ->whereBetween('t.end_date', [$start, $end])
                    ->groupBy('day_no', 'day')
                    ->orderBy('day_no')
                    ->get();
    
                $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                $map = [];
                foreach ($data as $row) {
                    $map[$row->day] = (float) $row->total;
                }
    
                return response()->json([
                    'success' => true,
                    'labels'  => $days,
                    'values'  => array_map(fn ($d) => $map[$d] ?? 0, $days)
                ]);
            }
    
            /**
             * =========================
             * MONTH → Jan–Dec (CURRENT YEAR)
             * =========================
             */
            if ($filter === 'month') {
                $data = (clone $baseQuery)
                    ->select(
                        DB::raw('MONTH(t.end_date) as month_no'),
                        DB::raw("DATE_FORMAT(t.end_date, '%b') as month"),
                        DB::raw('COALESCE(SUM(tr.prize_amount), 0) as total')
                    )
                    ->whereYear('t.end_date', now()->year)
                    ->groupBy('month_no', 'month')
                    ->orderBy('month_no')
                    ->get();
    
                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                $map = [];
                foreach ($data as $row) {
                    $map[$row->month] = (float) $row->total;
                }
    
                return response()->json([
                    'success' => true,
                    'labels'  => $months,
                    'values'  => array_map(fn ($m) => $map[$m] ?? 0, $months)
                ]);
            }
    
            /**
             * =========================
             * YEAR → Year-wise totals (all years)
             * =========================
             */
            if ($filter === 'year') {
                $data = (clone $baseQuery)
                    ->select(
                        DB::raw('YEAR(t.end_date) as year'),
                        DB::raw('COALESCE(SUM(tr.prize_amount), 0) as total')
                    )
                    ->groupBy('year')
                    ->orderBy('year')
                    ->get();
    
                return response()->json([
                    'success' => true,
                    'labels'  => $data->pluck('year'),
                    'values'  => $data->pluck('total')->map(fn ($v) => (float) $v)
                ]);
            }
    
            return response()->json([
                'success' => false,
                'message' => 'Invalid filter. Allowed: week, month, year'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // public function totalEarningsPerUser(Request $request)
    // {
    //     $filter = $request->query('filter'); // day | week | month | null
    //     $baseUrl = 'http://13.63.124.252/storage';

    //     $query = DB::table('tournament_registrations')
    //       ->select(
    //                 'tournament_registrations.user_id',
    //                 DB::raw('CAST(SUM(tournaments.prize_pool) AS UNSIGNED) as total_earnings'),
    //                 DB::raw("
    //                     MAX(
    //                         CASE
    //                             WHEN user_profiles.profile_image IS NOT NULL
    //                             THEN CONCAT('$baseUrl/', user_profiles.profile_image)
    //                             ELSE NULL
    //                         END
    //                     ) as profile_image
    //                 ")
    //             )
    //         ->join(
    //             'tournaments',
    //             'tournaments.winner_team_id',
    //             '=',
    //             'tournament_registrations.id'
    //         )
    //         ->leftJoin(
    //             'user_profiles',
    //             'user_profiles.user_id',
    //             '=',
    //             'tournament_registrations.user_id'
    //         )
    //         ->groupBy('tournament_registrations.user_id')
    //         ->orderByDesc('total_earnings');

    //     // Date filters
    //     if ($filter === 'day') {
    //         $query->whereDate('tournaments.end_date', now()->toDateString());
    //     } elseif ($filter === 'week') {
    //         $query->whereBetween('tournaments.end_date', [
    //             now()->startOfWeek(),
    //             now()->endOfWeek()
    //         ]);
    //     } elseif ($filter === 'month') {
    //         $query->whereMonth('tournaments.end_date', now()->month)
    //             ->whereYear('tournaments.end_date', now()->year);
    //     }

    //     $data = $query->get();

    //     return response()->json([
    //         'success' => true,
    //         'data' => $data
    //     ]);
    // }
    public function totalEarningsPerUser(Request $request)
    {
        try {
            // 1. Authenticate user
            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }
    
            $userId = $user->id;
            $filter = $request->query('filter'); // day | week | month | null
            $baseUrl = 'http://13.63.124.252/storage';
    
            // 2. Query for this specific user only
            $query = DB::table('tournament_registrations as tr')
                ->select(
                    'tr.user_id',
                    DB::raw('SUM(tr.prize_amount) as total_earnings'),
                    DB::raw('MAX(up.profile_image) as profile_image')
                )
                ->join('tournaments as t', 't.id', '=', 'tr.tournament_id')
                ->leftJoin('user_profiles as up', 'up.user_id', '=', 'tr.user_id')
                ->where('tr.user_id', $userId)   
                ->whereNotNull('tr.prize_amount')
                ->where('tr.prize_amount', '>', 0)
                ->where('t.end_date', '<', now())
                ->groupBy('tr.user_id');
    
            // Date filters (optional)
            if ($filter === 'day') {
                $query->whereDate('t.end_date', now()->toDateString());
            } elseif ($filter === 'week') {
                $query->whereBetween('t.end_date', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]);
            } elseif ($filter === 'month') {
                $query->whereMonth('t.end_date', now()->month)
                      ->whereYear('t.end_date', now()->year);
            }
    
            $data = $query->first(); // single record for the user
    
            // 3. Format response
            if ($data) {
                $data->profile_image = $data->profile_image 
                    ? $baseUrl . '/' . $data->profile_image 
                    : null;
            } else {
                $data = (object) [
                    'user_id' => $userId,
                    'total_earnings' => 0,
                    'profile_image' => null
                ];
            }
    
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
