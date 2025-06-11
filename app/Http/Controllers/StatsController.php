<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SiteVisit;

class StatsController extends Controller
{
    //
    public function index()
    {
        $siteVisit = SiteVisit::first();

        if (!$siteVisit) {
            $siteVisit = SiteVisit::create(['visits_count' => 1]);
        } else {
            $siteVisit->increment('visits_count');
        }

        $patientsCount = User::where('role', 'patient')->count();
        $doctorsCount = User::where('role', 'doctor')->count();

        return response()->json([
            'patients' => $patientsCount,
            'doctors' => $doctorsCount,
            'site_visits' => $siteVisit->visits_count,
        ]);
    }
}
