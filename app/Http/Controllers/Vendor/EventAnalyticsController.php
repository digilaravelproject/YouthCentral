<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventAnalyticsController extends Controller
{
    public function index(Request $request, Event $event)
    {
        // Check if the event belongs to the vendor
        if ($event->created_by !== auth()->id()) {
            abort(403);
        }

        $query = $event->registrations();

        // Apply date range filters if provided
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Basic stats
        $stats = [
            'total_registrations' => $query->count(),
            'total_paid' => $query->where('payment_status', 'paid')->count(),
            'total_pending' => $query->where('payment_status', 'pending')->count(),
            'total_amount' => $query->where('payment_status', 'paid')->sum('amount'),
            'seats_remaining' => $event->seat_limit ? ($event->seat_limit - $query->count()) : null,
        ];

        // Registration trend (last 30 days by default)
        $registrationTrend = $query->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // School type breakdown
        $ageBreakdown = $query->select('age_category', DB::raw('COUNT(*) as count'))
            ->groupBy('age_category')
            ->get();

        // Prepare data for charts
        $ageCategoryLabels = $ageBreakdown->pluck('age_category')->toArray();
        $ageCategoryCounts = $ageBreakdown->pluck('count')->toArray();

        // Payment status breakdown
        $paymentBreakdown = $query->select('payment_status', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_status')
            ->get();

        // Recent registrations
        $recentRegistrations = $query->with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('vendor.events.analytics', compact(
            'event',
            'stats',
            'registrationTrend',
            'ageBreakdown',
            'paymentBreakdown',
            'recentRegistrations'
        ));
    }
} 