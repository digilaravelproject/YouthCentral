<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YcIgnite;
use PDF;

class YcIgniteController extends Controller
{
    public function index()
    {
        $registrations = YcIgnite::with(['user', 'event'])
            ->latest()
            ->get();

        return view('admin.yc_ignites.index', compact('registrations'));
    }

    public function show($id)
    {
        $registration = YcIgnite::with('event')->findOrFail($id);

        return view('admin.yc_ignites.show', compact('registration'));
    }

    // public function downloadReceipt($id)
    // {
    //     $registration = \App\Models\YcIgnite::with('event')->findOrFail($id);

    //     $event = $registration->event;
    //     $company_name = config('app.name');
    //     $company_email = "support@youthcentral.com";
    //     $company_address = "Youth Central Office, Main Street, City";
    //     $receipt_id = "RCPT-" . str_pad($registration->id, 6, '0', STR_PAD_LEFT);
    //     $issued_date = now()->format('M d, Y');

    //     $pdf = PDF::loadView('pdfs.yc_ignite-receipt', compact(
    //         'registration',
    //         'event',
    //         'company_name',
    //         'company_email',
    //         'company_address',
    //         'receipt_id',
    //         'issued_date'
    //     ));

    //     return $pdf->download("receipt-{$receipt_id}.pdf");
    // }

    public function downloadReceipt($id)
    {
        $registration = \App\Models\YcIgnite::with('event')->findOrFail($id);

        // ðŸ” Authorization check
        if (auth()->id() !== $registration->user_id && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access to receipt');
        }

        $event = $registration->event;
        $company_name = config('app.name');
        $company_email = "support@youthcentral.com";
        $company_address = "Youth Central Office, Main Street, City";
        $receipt_id = "RCPT-" . str_pad($registration->id, 6, '0', STR_PAD_LEFT);
        $issued_date = now()->format('M d, Y');

        $pdf = \PDF::loadView('pdfs.yc_ignite-receipt', compact(
            'registration',
            'event',
            'company_name',
            'company_email',
            'company_address',
            'receipt_id',
            'issued_date'
        ));

        return $pdf->download("receipt-{$receipt_id}.pdf");
    }
}
