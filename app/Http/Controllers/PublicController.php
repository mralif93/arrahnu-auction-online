<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Collateral;
use App\Models\User;
use App\Models\Bid;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Show the homepage.
     */
    public function home()
    {
        return view('public.welcome');
    }

    /**
     * Show the about page.
     */
    public function about()
    {
        return view('public.about');
    }

    /**
     * Show the how it works page.
     */
    public function howItWorks()
    {
        return view('public.how-it-works');
    }

    /**
     * Show the public auctions listing page.
     */
    public function auctions()
    {
        // Get active auctions with their collaterals
        $activeAuctions = Auction::with(['collaterals.images', 'collaterals.bids', 'collaterals.highestBidder'])
                                ->where('status', 'active')
                                ->where('start_datetime', '<=', now())
                                ->where('end_datetime', '>', now())
                                ->orderBy('end_datetime', 'asc')
                                ->get();

        // Get completed auctions with their collaterals
        $completedAuctions = Auction::with(['collaterals.images', 'collaterals.bids', 'collaterals.highestBidder'])
                                   ->where('status', 'completed')
                                   ->orderBy('end_datetime', 'desc')
                                   ->take(10)
                                   ->get();

        // Get scheduled auctions (upcoming)
        $scheduledAuctions = Auction::with(['collaterals.images'])
                                   ->where('status', 'scheduled')
                                   ->where('start_datetime', '>', now())
                                   ->orderBy('start_datetime', 'asc')
                                   ->take(6)
                                   ->get();

        // Calculate statistics for the stats bar
        $liveAuctionsCount = $activeAuctions->count();
        $activeBiddersCount = User::whereHas('bids', function($query) {
            $query->whereHas('collateral.auction', function($auctionQuery) {
                $auctionQuery->where('status', 'active');
            });
        })->distinct()->count();

        // Calculate total value from active auctions
        $totalValue = $activeAuctions->sum(function($auction) {
            return $auction->collaterals->sum('current_highest_bid_rm');
        });

        // Count auctions ending today
        $endingTodayCount = $activeAuctions->filter(function($auction) {
            return $auction->end_datetime->isToday();
        })->count();

        return view('public.auctions.index', compact(
            'activeAuctions',
            'completedAuctions',
            'scheduledAuctions',
            'liveAuctionsCount',
            'activeBiddersCount',
            'totalValue',
            'endingTodayCount'
        ));
    }

    /**
     * Show individual collateral auction details.
     */
    public function auctionDetails(Collateral $collateral)
    {
        // Only show collaterals from auctions that are active or completed to public
        if (!in_array($collateral->auction->status, ['active', 'completed'])) {
            abort(404);
        }

        $collateral->load([
            'auction',
            'account.branch',
            'images',
            'bids.user',
            'highestBidder',
            'creator',
            'approvedBy'
        ]);

        // Get related collaterals from other auctions
        $relatedAuctions = Collateral::with(['images', 'bids', 'auction'])
                                    ->where('id', '!=', $collateral->id)
                                    ->whereHas('auction', function($query) {
                                        $query->whereIn('status', ['active', 'completed']);
                                    })
                                    ->orderBy('created_at', 'desc')
                                    ->take(4)
                                    ->get();

        return view('public.auctions.show', compact('collateral', 'relatedAuctions'));
    }

    /**
     * Show user dashboard.
     */
    public function dashboard()
    {
        return view('public.dashboard');
    }
}
