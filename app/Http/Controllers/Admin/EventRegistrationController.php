<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventRegistrationController extends Controller
{
    /**
     * Display a listing of all event registrations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = EventRegistration::with(['event', 'user'])
            ->orderBy('created_at', 'desc');
            
        // Filter by event if an event_id is provided
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }
        
        // Filter by payment status if provided
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Filter by date range if provided
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $registrations = $query->paginate(15);
        $events = Event::orderBy('title')->get();
        
        return view('admin.event-registrations.index', compact('registrations', 'events'));
    }
    
    /**
     * Show the form for filtering registrations.
     *
     * @return \Illuminate\Http\Response
     */
    public function filter()
    {
        $events = Event::orderBy('title')->get();
        return view('admin.event-registrations.filter', compact('events'));
    }
    
    /**
     * Display details for a specific registration.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $registration = EventRegistration::with(['event', 'user'])->findOrFail($id);
        return view('admin.event-registrations.show', compact('registration'));
    }
    
    /**
     * Download a PDF receipt for event registration
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//     public function downloadReceipt($id)
//     {
//         $registration = EventRegistration::with('event')->findOrFail($id);
        
//         // Ensure the registration is paid
//         if ($registration->payment_status !== 'paid') {
//             return redirect()->back()->with('error', 'Receipt is only available for completed payments.');
//         }
        
//         $data = [
//             'registration' => $registration,
//             'event' => $registration->event,
//             'issued_date' => now()->format('M d, Y'),
//             'company_name' => config('app.name'),
// 'company_address' => 'Youth Central<br>
// By Youthcentuary Academy Pvt Ltd<br>
// Palm Beach Society, Sector 4 Nerul,<br>
// Navi Mumbai',
//             'company_email' => 'support@youthcentral.com',
//             'receipt_id' => 'RCPT-' . $registration->id . '-' . substr($registration->razorpay_payment_id, -6)
//         ];
        
//         $pdf = \PDF::loadView('pdfs.event-receipt', $data);
        
//         return $pdf->download('event-receipt-' . $registration->id . '.pdf');
//     }

public function downloadReceipt($id)
{
    $registration = EventRegistration::with('event')->findOrFail($id);
    
    // Ensure the registration is paid
    if ($registration->payment_status !== 'paid') {
        return redirect()->back()->with('error', 'Receipt is only available for completed payments.');
    }
    
    $data = [
        'registration' => $registration,
        'event' => $registration->event,
        'issued_date' => now()->format('M d, Y'),
        'company_name' => config('app.name'),
        'company_address' => 'Youth Central<br>By Youthcentuary Academy Pvt Ltd<br>Palm Beach Society, Sector 4 Nerul,<br>Navi Mumbai',
        'company_email' => 'info@youthcentral.co',
        'receipt_id' => 'RCPT-' . $registration->id . '-' . substr($registration->razorpay_payment_id, -6),
        'currency_symbol' => config('app.currency_symbol', '₹') // Default to ₹ if not set in config
    ];
    
    $pdf = \PDF::loadView('pdfs.event-receipt', $data)
        ->setOptions([
            'isHtml5ParserEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'defaultFont' => 'DejaVu Sans'
        ]);
    $string = $registration->event->title;
    $result = str_replace(" ", "_", $string);
    return $pdf->download($registration->first_name . '_' . $registration->last_name . '_'. $result . '.pdf');
}
} 
 
 