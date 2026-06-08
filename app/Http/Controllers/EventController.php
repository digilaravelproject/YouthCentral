<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('status', 'approved')
            ->where('end_date', '>=', now())
            ->orderBy('start_date')
            ->paginate(12);
            
        return view('public.events.index', compact('events'));
    }

    public function userRegistrations()
    {
        $registrations = EventRegistration::where('user_id', Auth::id())
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('user.events.registrations', compact('registrations'));
    }

    public function register(Event $event)
    {
        return view('public.events.register', compact('event'));
    }

    public function submitRegistration(Request $request, Event $event)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'age_category' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|email',
            'mobile' => 'required|string',
            'school' => 'required|string',
            'grade' => 'required|string',
            'sport_event' => 'required|string',
        ]);

        $registration = new EventRegistration($validated);
        $registration->event_id = $event->id;
        $registration->user_id = Auth::id() ?? null;
        $registration->amount = $event->registration_amount;
        $registration->save();

        // Initialize Razorpay payment
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        $order = $api->order->create([
            'amount' => $registration->amount * 100, // Amount in paise
            'currency' => 'INR',
            'receipt' => 'rcpt_' . $registration->id,
        ]);

        $registration->razorpay_order_id = $order->id;
        $registration->save();

        // Create payment record
        $payment = new EventPayment([
            'event_registration_id' => $registration->id,
            'amount' => $registration->amount,
            'currency' => 'INR',
            'status' => 'created',
            'razorpay_order_id' => $order->id,
        ]);
        $payment->save();

        return view('public.events.payment', [
            'registration' => $registration,
            'event' => $event,
            'order' => $order,
        ]);
    }

    public function handlePayment(Request $request)
    {
        $registration = EventRegistration::where('razorpay_order_id', $request->razorpay_order_id)->firstOrFail();
        $payment = $registration->payment;

        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $payment->razorpay_payment_id = $request->razorpay_payment_id;
            $payment->status = 'captured';
            $payment->payment_details = $request->all();
            $payment->paid_at = now();
            $payment->save();

            $registration->payment_status = 'paid';
            $registration->razorpay_payment_id = $request->razorpay_payment_id;
            $registration->save();

            // Send email
            Mail::to($registration->email)->send(new \App\Mail\EventRegistrationConfirmation($registration));

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            $payment->status = 'failed';
            $payment->payment_details = array_merge($request->all(), ['error' => $e->getMessage()]);
            $payment->save();

            $registration->payment_status = 'failed';
            $registration->save();

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
} 