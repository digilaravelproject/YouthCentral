<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    /**
     * Show the student dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        $registration = \App\Models\EventRegistration::where('mobile', $student->phone)->first();
        $studentClass = $registration ? $registration->grade : 'N/A';

        // Compute dynamic progress
        $progressService = app(\App\Services\StudentProgressService::class);
        $progressData = $progressService->getProgress($student);
        $overallPercentage = $progressData['overall_percentage'];
        
        // Calculate the next Jan 27 for YC SPARK exam
        $currentYear = date('Y');
        $targetDateThisYear = \Carbon\Carbon::createFromDate($currentYear, 1, 27)->startOfDay();
        
        if (now()->isAfter($targetDateThisYear)) {
            $examDate = \Carbon\Carbon::createFromDate($currentYear + 1, 1, 27)->startOfDay()->format('Y-m-d H:i:s');
        } else {
            $examDate = $targetDateThisYear->format('Y-m-d H:i:s');
        }
        
        // Compute assigned study materials and model question papers count
        $registrationIds = \App\Models\EventRegistration::where('mobile', $student->phone)->pluck('id');
        $materialsCount = \App\Models\StudyMaterial::whereHas('students', function ($q) use ($registrationIds) {
            $q->whereIn('event_registration_id', $registrationIds);
        })->where('status', true)->count();

        $papersCount = \App\Models\ModelQuestionPaper::whereHas('students', function ($q) use ($registrationIds) {
            $q->whereIn('event_registration_id', $registrationIds);
        })->where('status', true)->count();

        // Recent notifications for dashboard (limit 5)
        $notifications = $student->notifications()->orderBy('created_at', 'desc')->limit(5)->get();

        return view('student.dashboard', compact('student', 'studentClass', 'examDate', 'materialsCount', 'papersCount', 'notifications', 'overallPercentage'));
    }
}
