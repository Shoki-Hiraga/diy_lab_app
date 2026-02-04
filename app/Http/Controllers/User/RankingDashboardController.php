<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PostEngagementStat;
use Carbon\Carbon;

class RankingDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return view('users.dashboard', [
                'dashboard' => null,
            ]);
        }

        $postIds = $user->posts()->pluck('id');

        $dashboard = [
            'today_views' => PostEngagementStat::whereIn('post_id', $postIds)
                ->where('date', Carbon::today())
                ->sum('view_count'),

            'today_likes' => PostEngagementStat::whereIn('post_id', $postIds)
                ->where('date', Carbon::today())
                ->sum('like_count'),

            'today_reactions' => PostEngagementStat::whereIn('post_id', $postIds)
                ->where('date', Carbon::today())
                ->sum('reaction_count'),

            'total_views' => PostEngagementStat::whereIn('post_id', $postIds)
                ->sum('view_count'),
        ];

        return view('users.dashboard', compact('dashboard'));
    }
}
