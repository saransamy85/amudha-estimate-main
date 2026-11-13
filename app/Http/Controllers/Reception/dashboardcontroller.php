<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\customers;
use App\Models\MaterialDispatch;
use App\Models\MaterialDispatchItem;
use App\Models\MaterialReturn;
use Carbon\Carbon;
use Illuminate\Http\Request;

class dashboardcontroller extends Controller
{
    public function index()
    {
        $totalCustomers = customers::count();

        $yetToStart = customers::where('status', 'Yet to Start')->count();

        $progress = customers::where('status', 'Progress')->count();

        $completed = customers::where('status', 'Completed')->count();

        $activeSitesCount = $yetToStart + $progress;

        // Dispatches metrics
        $totalDispatches = MaterialDispatch::count();
        $todayDispatches = MaterialDispatch::whereDate('dispatch_date', Carbon::today())->count();
        $totalTransport = (float) (MaterialDispatch::sum('transport_charge') ?? 0);

        // Returns metrics
        $totalReturns = MaterialReturn::count();
        $todayReturns = MaterialReturn::whereDate('return_date', Carbon::today())->count();

        // Recent Dispatches with customer & items
        $dispatches = MaterialDispatch::with([
            'customer',
            'items'
        ])
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->get();

        // Recent Inward Returns with customer & items
        $recentReturns = MaterialReturn::with([
            'customer',
            'items.dispatchItem'
        ])
            ->orderBy('id', 'DESC')
            ->limit(5)
            ->get();

        // Active project sites with dispatch relationships
        $activeSites = customers::with('materialDispatches')
            ->whereIn('status', ['Yet to Start', 'Progress'])
            ->orderBy('id', 'DESC')
            ->limit(5)
            ->get();

        return view('Reception/dashboard', compact(
            'dispatches',
            'totalCustomers',
            'yetToStart',
            'progress',
            'completed',
            'activeSitesCount',
            'totalDispatches',
            'todayDispatches',
            'totalReturns',
            'todayReturns',
            'totalTransport',
            'recentReturns',
            'activeSites'
        ));
    }

    public function sites()
    {
        $sites = customers::with('materialDispatches')
            ->whereIn('status', ['Yet to Start', 'Progress'])
            ->orderBy('created_at', 'DESC')
            ->get();

        $cli = customers::with('materialDispatches')
            ->orderBy('created_at', 'DESC')
            ->get();

        $totalSitesCount = $cli->count();
        $activeSitesCount = $sites->count();
        $progressCount = $cli->where('status', 'Progress')->count();
        $yetToStartCount = $cli->where('status', 'Yet to Start')->count();
        $completedCount = $cli->where('status', 'Completed')->count();
        $totalFreightSum = (float) (MaterialDispatch::sum('transport_charge') ?? 0);

        return view('Reception/sites', compact(
            'cli',
            'sites',
            'totalSitesCount',
            'activeSitesCount',
            'progressCount',
            'yetToStartCount',
            'completedCount',
            'totalFreightSum'
        ));
    }

    public function siteTimeline($id)
    {
        $customer = customers::findOrFail($id);

        $dispatches = MaterialDispatch::with('items')
            ->where('customer_id', $id)
            ->orderBy('dispatch_date', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $dispatchCount = $dispatches->count();

        $transportTotal = (float) $dispatches->sum('transport_charge');

        $materialCount = 0;
        foreach ($dispatches as $dispatch) {
            $materialCount += $dispatch->items->count();
        }

        $returns = MaterialReturn::with(['items.dispatchItem'])
            ->where('customer_id', $id)
            ->orderBy('return_date', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $returnsCount = $returns->count();
        $returnedItemsCount = 0;
        foreach ($returns as $ret) {
            $returnedItemsCount += $ret->items->count();
        }

        return view('Reception/timeline', compact(
            'customer',
            'dispatches',
            'dispatchCount',
            'transportTotal',
            'materialCount',
            'returns',
            'returnsCount',
            'returnedItemsCount'
        ));
    }

    public function history()
    {
        $returns = MaterialReturn::with([
            'customer',
            'items.dispatchItem'
        ])
            ->latest()
            ->paginate(10);

        return view('Reception.returnhistory', compact('returns'));
    }

    public function returnview($id)
    {
        $return = MaterialReturn::with([
            'customer',
            'items.dispatchItem'
        ])->findOrFail($id);

        return view(
            'Reception.returnview',
            compact('return')
        );
    }
}
