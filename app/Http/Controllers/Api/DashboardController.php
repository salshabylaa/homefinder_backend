<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $totalProperti = 0;
        $propertiTerjual = 0;
        
        if ($user->role === 'homeadvisor') {
            $totalProperti = Listing::where('user_id', $user->id)->count();
            $propertiTerjual = Listing::where('user_id', $user->id)->where('status', 'sold_out')->count();
        } else {
            $totalProperti = Listing::count();
            $propertiTerjual = Listing::where('status', 'sold_out')->count();
        }
        
        $homeAdvisorAktif = User::where('role', 'homeadvisor')->count();
        
        // Retrieve real-time visits from cache (actual unique visits or page views tracked this month)
        $cacheKey = 'website_visits_' . date('Y_m');
        $realVisits = \Illuminate\Support\Facades\Cache::get($cacheKey, 0);
        $totalVisits = $realVisits;
        
        // Format the number nicely (e.g. 1,205 instead of 1.2K so increments are visible)
        $kunjungan = number_format($totalVisits, 0, ',', '.');

        // Generate Recent Activities
        $activities = [];

        // 1. New Properties / Updated Properties
        $recentListings = Listing::with('user')->orderBy('updated_at', 'desc')->take(5)->get();
        foreach ($recentListings as $listing) {
            $activities[] = [
                'type' => $listing->status === 'sold_out' ? 'sold_out' : 'new_property',
                'user' => $listing->user->name,
                'target' => $listing->title,
                'date' => $listing->updated_at,
                'time_ago' => $listing->updated_at->diffForHumans()
            ];
        }

        // 2. Approved Applications (Only show for superadmin)
        if ($user->role === 'superadmin') {
            $recentApps = \Illuminate\Support\Facades\DB::table('applications')
                ->where('status_crm', 'Disetujui')
                ->orderBy('updated_at', 'desc')
                ->take(3)
                ->get();
            
            foreach ($recentApps as $app) {
                $activities[] = [
                    'type' => 'approved_application',
                    'user' => 'Sistem',
                    'target' => $app->nama_lengkap,
                    'date' => \Carbon\Carbon::parse($app->updated_at),
                    'time_ago' => \Carbon\Carbon::parse($app->updated_at)->diffForHumans()
                ];
            }
        }

        // Sort activities by date descending
        usort($activities, function ($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        // Take top 5
        $recentActivities = array_slice($activities, 0, 5);

        return response()->json([
            'data' => [
                'total_properti' => $totalProperti,
                'home_advisor_aktif' => $homeAdvisorAktif,
                'properti_terjual' => $propertiTerjual,
                'kunjungan_bulan_ini' => $kunjungan,
                'recent_activities' => $recentActivities,
            ]
        ]);
    }
}

