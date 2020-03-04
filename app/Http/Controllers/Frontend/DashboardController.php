<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Dashboard - Analytics
    public function dashboardAnalytics(){
        $pageConfigs = [
            'pageHeader' => false
        ];

        return view('frontend.pages.dashboard-analytics', [
            'pageConfigs' => $pageConfigs
        ]);
    }

}

