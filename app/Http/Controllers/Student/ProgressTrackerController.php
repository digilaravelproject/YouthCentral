<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\StudentProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressTrackerController extends Controller
{
    protected $progressService;

    public function __construct(StudentProgressService $progressService)
    {
        $this->middleware('auth:student');
        $this->progressService = $progressService;
    }

    /**
     * Display the student's progress report card.
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        $registration = \App\Models\EventRegistration::where('mobile', $student->phone)->first();
        $studentClass = $registration ? $registration->grade : 'N/A';
        
        $progressData = $this->progressService->getProgress($student);

        $activities = $progressData['activities'];
        $overallPercentage = $progressData['overall_percentage'];
        $totalEarned = $progressData['total_earned_percentage'];
        $totalConfigured = $progressData['total_configured_percentage'];

        return view('student.progress-tracker.index', compact(
            'student',
            'studentClass',
            'activities',
            'overallPercentage',
            'totalEarned',
            'totalConfigured'
        ));
    }
}
