<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DashboardImage;
use App\Models\TournamentRegistration;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
    $baseUrl = 'https://www.markupdesigns.net/qatar-esports/storage';

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


public function tournamentPrizeDistribution(Request $request)
{
    $filter = $request->query('filter', 'week'); // week | month | year

    /**
     * =========================
     * WEEK → Mon–Sun (DEFAULT)
     * =========================
     */
    if ($filter === 'week') {

        $data = DB::table('tournaments')
            ->select(
                DB::raw('WEEKDAY(end_date) as day_no'), // 0=Mon
                DB::raw('DAYNAME(end_date) as day'),
                DB::raw('SUM(prize_pool) as total')
            )
            ->whereNotNull('winner_team_id')
            ->whereBetween('end_date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->groupBy('day_no', 'day')
            ->orderBy('day_no')
            ->get();

        $days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $map = [];

        foreach ($data as $row) {
            $map[substr($row->day, 0, 3)] = (float) $row->total;
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

        $data = DB::table('tournaments')
            ->select(
                DB::raw('MONTH(end_date) as month_no'),
                DB::raw('MONTHNAME(end_date) as month'),
                DB::raw('SUM(prize_pool) as total')
            )
            ->whereNotNull('winner_team_id')
            ->whereYear('end_date', now()->year)
            ->groupBy('month_no', 'month')
            ->orderBy('month_no')
            ->get();

        $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $map = [];

        foreach ($data as $row) {
            $map[substr($row->month, 0, 3)] = (float) $row->total;
        }

        return response()->json([
            'success' => true,
            'labels'  => $months,
            'values'  => array_map(fn ($m) => $map[$m] ?? 0, $months)
        ]);
    }

    /**
     * =========================
     * YEAR → Year-wise totals
     * =========================
     */
    if ($filter === 'year') {

        $data = DB::table('tournaments')
            ->select(
                DB::raw('YEAR(end_date) as year'),
                DB::raw('SUM(prize_pool) as total')
            )
            ->whereNotNull('winner_team_id')
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
        'message' => 'Invalid filter'
    ], 400);
}


// public function tournamentPrizeDistribution(Request $request)
// {
//     $filter = $request->query('filter', 'week');

//     /**
//      * =========================
//      * DAY → Daily (Last 7 Days)
//      * =========================
//      */
//     if ($filter === 'day') {

//         $data = DB::table('tournaments')
//             ->select(
//                 DB::raw('DATE(end_date) as date'),
//                 DB::raw('SUM(prize_pool) as total')
//             )
//             ->whereNotNull('winner_team_id')
//             ->whereDate('end_date', '>=', now()->subDays(6))
//             ->groupBy(DB::raw('DATE(end_date)'))
//             ->orderBy('date')
//             ->get();

//         $labels = [];
//         $values = [];

//         foreach ($data as $row) {
//             $labels[] = date('d M', strtotime($row->date));
//             $values[] = (float) $row->total;
//         }

//         return response()->json([
//             'success' => true,
//             'labels' => $labels,
//             'values' => $values
//         ]);
//     }

//     /**
//      * =========================
//      * WEEK → Mon–Sun
//      * =========================
//      */
//     if ($filter === 'week') {

//         $data = DB::table('tournaments')
//             ->select(
//                 DB::raw('DAYOFWEEK(end_date) as day_no'),
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
//             'labels' => $days,
//             'values' => array_map(fn ($d) => $map[$d] ?? 0, $days)
//         ]);
//     }

//     /**
//      * =========================
//      * MONTH → Jan–Dec
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
//             'labels' => $months,
//             'values' => array_map(fn ($m) => $map[$m] ?? 0, $months)
//         ]);
//     }

//     return response()->json([
//         'success' => false,
//         'message' => 'Invalid filter'
//     ], 400);
// }

    public function totalEarningsPerUser(Request $request)
    {
        $filter = $request->query('filter'); // day | week | month | null
        $baseUrl = 'https://www.markupdesigns.net/qatar-esports/storage';

        $query = DB::table('tournament_registrations')
           ->select(
                    'tournament_registrations.user_id',
                    DB::raw('CAST(SUM(tournaments.prize_pool) AS UNSIGNED) as total_earnings'),
                    DB::raw("
                        MAX(
                            CASE
                                WHEN user_profiles.profile_image IS NOT NULL
                                THEN CONCAT('$baseUrl/', user_profiles.profile_image)
                                ELSE NULL
                            END
                        ) as profile_image
                    ")
                )
            ->join(
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
            ->orderByDesc('total_earnings');

        // Date filters
        if ($filter === 'day') {
            $query->whereDate('tournaments.end_date', now()->toDateString());
        } elseif ($filter === 'week') {
            $query->whereBetween('tournaments.end_date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        } elseif ($filter === 'month') {
            $query->whereMonth('tournaments.end_date', now()->month)
                ->whereYear('tournaments.end_date', now()->year);
        }

        $data = $query->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }


  
}
