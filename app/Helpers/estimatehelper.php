<?php

use App\Models\estimate;

if (!function_exists('generateEstimateNumber')) {
    function generateEstimateNumber()
    {
        $currentYear = date('Y');
        $currentMonth = date('n');

        if ($currentMonth >= 4) {
            $fyStart = $currentYear;
            $fyEnd = $currentYear + 1;
        } else {
            $fyStart = $currentYear - 1;
            $fyEnd = $currentYear;
        }

        $financialYear = $fyStart . '-' . substr($fyEnd, -2);

        $lastEstimate = estimate::where('estimate_no', 'like', '%/AD/' . $financialYear)
            ->orderByDesc('id')
            ->first();

        if ($lastEstimate) {
            $parts = explode('/', $lastEstimate->estimate_no);
            $nextNumber = ((int) $parts[0]) + 1;
        } else {
            $nextNumber = 1;
        }

        return $nextNumber . '/AD/' . $financialYear;
    }
}
