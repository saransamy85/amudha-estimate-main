<?php

namespace App\Http\Controllers;

use App\Models\customers;
use App\Models\leads;
use App\Services\RcsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RcsController extends Controller
{
    public function rcsmsg()
    {
        $customerMobiles = customers::whereNotNull('Mobile')->where('Mobile', '!=', '')->pluck('Mobile')->unique()->values();
        $leadMobiles = leads::whereNotNull('Mobile')->where('Mobile', '!=', '')->pluck('Mobile')->unique()->values();
        return view('admin.rcs', compact('customerMobiles', 'leadMobiles'));
    }

    public function sendTextOnly(Request $request)
    {
        // ✅ Validate input
        $request->validate([
            'template' => 'required|string',
        ]);

        $rawInput = $request->input('numbers') ?? $request->input('mobile');

        if (empty($rawInput)) {
            return back()->withErrors(['numbers' => 'Please enter at least one mobile number.']);
        }

        $approvedTemplates = [
            'Republic_2026',
            'Welcome_MSG',
            'Tensile',
            'Products',
            'Ind_2026',
            'AyuthaPooja2026',
            'Welcome__Message'
        ];

        if (!in_array($request->template, $approvedTemplates)) {
            return back()->withErrors(['template' => 'Invalid template selected']);
        }

        // Split numbers by comma, newline, carriage return, space, semicolon
        $rawNumbers = preg_split('/[\s,;\n\r]+/', $rawInput, -1, PREG_SPLIT_NO_EMPTY);
        $validNumbers = [];

        foreach ($rawNumbers as $num) {
            $cleaned = preg_replace('/\D/', '', $num);
            if (strlen($cleaned) === 10) {
                $cleaned = '91' . $cleaned;
            } elseif (strlen($cleaned) === 11 && str_starts_with($cleaned, '0')) {
                $cleaned = '91' . substr($cleaned, 1);
            }
            if (strlen($cleaned) === 12 && str_starts_with($cleaned, '91')) {
                $validNumbers[] = $cleaned;
            }
        }

        if ($request->has('remove_duplicate') || $request->input('remove_duplicate') == '1' || $request->input('remove_duplicate') == 'on') {
            $validNumbers = array_values(array_unique($validNumbers));
        }

        if (empty($validNumbers)) {
            return back()->withErrors(['numbers' => 'Please enter valid 10-digit Indian mobile numbers.']);
        }

        if (count($validNumbers) > 5000) {
            return back()->withErrors(['numbers' => 'Maximum limit is 5,000 numbers per campaign.']);
        }

        $campaignName = $request->input('campaign_name', 'ui_send');

        // ✅ Send RCS
        $response = RcsService::send(
            $validNumbers,
            $request->template, // approved template
            [],
            $campaignName
        );

        if (empty($response['success'])) {
            return back()->withErrors([
                'error' => $response['message'] ?? 'Failed to send RCS message.'
            ]);
        }

        return back()->with('success', $response['message'] ?? 'RCS message sent successfully to ' . count($validNumbers) . ' recipient(s)!');
    }
}
