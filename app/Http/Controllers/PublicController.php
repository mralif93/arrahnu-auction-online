<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Collateral;
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
        $activeAuctions = Auction::with(['branch', 'collaterals.images', 'collaterals.bids', 'collaterals.highestBidder'])
                                ->where('status', 'active')
                                ->where('start_datetime', '<=', now())
                                ->where('end_datetime', '>', now())
                                ->orderBy('end_datetime', 'asc')
                                ->get();

        // Get completed auctions with their collaterals
        $completedAuctions = Auction::with(['branch', 'collaterals.images', 'collaterals.bids', 'collaterals.highestBidder'])
                                   ->where('status', 'completed')
                                   ->orderBy('end_datetime', 'desc')
                                   ->take(10)
                                   ->get();

        // Get scheduled auctions (upcoming)
        $scheduledAuctions = Auction::with(['branch', 'collaterals.images'])
                                   ->where('status', 'scheduled')
                                   ->where('start_datetime', '>', now())
                                   ->orderBy('start_datetime', 'asc')
                                   ->take(6)
                                   ->get();

        return view('public.auctions.index', compact('activeAuctions', 'completedAuctions', 'scheduledAuctions'));
    }

    /**
     * Show individual auction details.
     */
    public function auctionDetails(Auction $auction)
    {
        // Only show auctions that are active or completed to public
        if (!in_array($auction->status, ['active', 'completed'])) {
            abort(404);
        }

        $auction->load([
            'branch',
            'collaterals.images',
            'collaterals.bids.user',
            'collaterals.highestBidder',
            'creator',
            'approvedBy'
        ]);

        // Get related auctions from same branch
        $relatedAuctions = Auction::with(['branch', 'collaterals.images'])
                                 ->where('branch_id', $auction->branch_id)
                                 ->where('id', '!=', $auction->id)
                                 ->whereIn('status', ['active', 'completed'])
                                 ->orderBy('start_datetime', 'desc')
                                 ->take(4)
                                 ->get();

        return view('public.auctions.show', compact('auction', 'relatedAuctions'));
    }

    /**
     * Show user dashboard.
     */
    public function dashboard()
    {
        return view('public.dashboard');
    }
}
