<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\customers;
use App\Models\estimate;
use App\Models\leads;
use App\Models\PurchaseOrder;
use App\Models\MaterialDispatch;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AccountsController extends Controller
{
    //
    public function home()
    {
        return view('Accounts/home');
    }

    public function customerSearch(Request $request)
    {
        $lead = null;
        $estimates = collect();
        $purchaseOrders = collect();
        $materialDispatches = collect();
        $recentLeads = leads::latest()->take(6)->get();

        if ($request->filled('lead_id')) {
            $searchKey = trim($request->lead_id);

            // 1. Try finding by ID or exact match
            if (is_numeric($searchKey)) {
                $lead = leads::find($searchKey) ?? leads::where('Mobile', $searchKey)->first();
            } else {
                $lead = leads::where('Name', 'like', "%{$searchKey}%")
                    ->orWhere('Mobile', 'like', "%{$searchKey}%")
                    ->first();
            }

            // 2. Fallback: check if customer record matches
            if (!$lead) {
                $cust = customers::where('Mobile', $searchKey)
                    ->orWhere('client_name', 'like', "%{$searchKey}%")
                    ->first();
                if ($cust && $cust->lead_id) {
                    $lead = leads::find($cust->lead_id);
                }
            }

            if ($lead) {
                // All Estimates for this Lead
                $estimates = estimate::with('customer')
                    ->where('lead_id', $lead->id)
                    ->latest()
                    ->get();

                // Customer IDs from estimates & direct customer associations
                $customerIds = $estimates
                    ->pluck('customer.id')
                    ->filter()
                    ->toArray();

                $directCustomerIds = customers::where('lead_id', $lead->id)->pluck('id')->toArray();
                $customerIds = array_values(array_unique(array_merge($customerIds, $directCustomerIds)));

                // Purchase Orders (eager load vendor & customer)
                $purchaseOrders = PurchaseOrder::with(['pocustomer', 'vendor'])
                    ->whereIn('site_id', $customerIds)
                    ->latest()
                    ->get();

                // Material Dispatches
                $materialDispatches = MaterialDispatch::with('customer')
                    ->whereIn('customer_id', $customerIds)
                    ->latest()
                    ->get();
            }
        }

        return view(
            'Accounts.customersearch',
            compact('lead', 'estimates', 'purchaseOrders', 'materialDispatches', 'recentLeads')    
        );
    }

    public function customerReportPdf($lead_id)
    {
        // Lead Details
        $lead = Leads::findOrFail($lead_id);

        // Estimate Details
        $estimates = Estimate::with('customer')
            ->where('lead_id', $lead_id)
                ->get();

        // Customer IDs
        $customerIds = $estimates
            ->pluck('customer.id')
            ->filter()
        ->toArray();

        // Purchase Orders
        $purchaseOrders = PurchaseOrder::with(['vendor', 'customer'])
            ->whereIn('site_id', $customerIds)
            ->get();

        // Material Dispatches
        $materialDispatches = MaterialDispatch::with('customer')
            ->whereIn('customer_id', $customerIds)
            ->get();

        $pdf = Pdf::loadView(
            'Accounts.customer-report-pdf',
            compact('lead', 'estimates', 'purchaseOrders', 'materialDispatches')
        );

        return $pdf->stream('Customer_Report_'.$lead->id.'.pdf');
    }


}
