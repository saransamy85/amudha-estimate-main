<?php

namespace App\Http\Controllers;

use App\Models\customers;
use App\Models\estimate;
use App\Models\leads;
use Illuminate\Http\Request;

class customercontroller extends Controller
{
    //
    public function clients()
    {
        $cli = customers::all();
        return view('admin/customer', compact('cli'));
    }

    public function clientstore(Request $request)
    {
        $custom = customers::create([
            'client_name' => $request->cusname,
            'Mobile' => $request->cusmob,
            'Location' => $request->cuslocation,
            'Area' => $request->cusarea,
            'Product' => $request->cusproduct,
            'Total_values' => $request->cusvalue,
        ]);
        return back()->with(
            'success',
            'Project Status Updated Successfully.'
        );
    }

    public function convertToCustomer($id)
    {
        $estimate = estimate::with('lead')->findOrFail($id);

        $lead = $estimate->lead;
        $area = ($lead && $lead->Total_Area) ? $lead->Total_Area : ($estimate->description ?: '-');
        $product = ($lead && $lead->Product) ? $lead->Product : ($estimate->description ?: 'General');
        $location = $estimate->customer_address ?: ($lead && $lead->Site_location ? $lead->Site_location : '-');
        $clientName = $estimate->customer_name ?: ($lead && $lead->Name ? $lead->Name : 'Client');
        $mobile = $estimate->mobile ?: ($lead && $lead->Mobile ? $lead->Mobile : '-');
        $netTotal = $estimate->net_total ?? 0;

        // Check if customer with this mobile already exists
        $exists = null;
        if (!empty($mobile) && $mobile !== '-') {
            $exists = customers::where('Mobile', $mobile)->first();
        }

        if ($exists) {
            // Link existing customer to this estimate
            $exists->estimate_id = $estimate->id;
            if ($estimate->lead_id && !$exists->lead_id) {
                $exists->lead_id = $estimate->lead_id;
            }
            if ($netTotal) {
                $exists->Total_values = $netTotal;
            }
            if (empty($exists->Product) || $exists->Product === '-') {
                $exists->Product = $product;
            }
            if (empty($exists->Location) || $exists->Location === '-') {
                $exists->Location = $location;
            }
            if (empty($exists->Area) || $exists->Area === '-') {
                $exists->Area = $area;
            }
            $exists->save();

            if ($lead) {
                $lead->update(['Status' => 'Confirmed']);
            }

            return back()->with(
                'success',
                "Estimate #{$estimate->estimate_no} converted and linked to existing customer ({$exists->client_name}) successfully."
            );
        }

        // Create new customer record
        customers::create([
            'client_name' => $clientName,
            'Mobile' => $mobile,
            'Location' => $location ?: '-',
            'Area' => $area ?: '-',
            'Product' => $product ?: '-',
            'Total_values' => $netTotal,
            'status' => 'Yet to Start',
            'lead_id' => $estimate->lead_id,
            'estimate_id' => $estimate->id,
        ]);

        if ($lead) {
            $lead->update(['Status' => 'Confirmed']);
        }

        return back()->with(
            'success',
            "Estimate #{$estimate->estimate_no} converted to customer ({$clientName}) successfully."
        );
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $customer = customers::findOrFail($id);

        $customer->status = $request->status;

        $customer->save();

        return back()->with(
            'success',
            'Project Status Updated Successfully.'
        );
    }
}
