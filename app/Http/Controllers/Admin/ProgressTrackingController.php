<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgressActivity;
use App\Models\Student;
use App\Services\StudentProgressService;
use Illuminate\Http\Request;

class ProgressTrackingController extends Controller
{
    protected $progressService;

    public static $activityTypes = [
        'study_material_view' => 'Study materials viewed',
        'study_material_download' => 'Study materials download',
        'model_question_paper_download' => 'Model question papers downloads',
        'model_question_paper_view' => 'Model question papers viewed',
        'paper_completed' => 'Papers completed',
        'login_streak' => 'Login streak',
    ];

    public function __construct(StudentProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    /**
     * Display a listing of rules and student progress.
     */
    public function index(Request $request)
    {
        $rules = ProgressActivity::orderBy('id', 'asc')->get();

        $query = Student::with('eventRegistration');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('student_class', 'like', "%{$search}%");
            });
        }

        $students = $query->latest()->paginate(15)->withQueryString();

        // Attach computed progress to each student
        foreach ($students as $student) {
            $progressData = $this->progressService->getProgress($student);
            $student->overall_progress = $progressData['overall_percentage'];
            $student->progress_details = $progressData['activities'];
        }

        return view('admin.progress-tracking.index', compact('rules', 'students'));
    }

    /**
     * Show the form for creating a new progress tracking activity rule.
     */
    public function create()
    {
        // Only show types that are not yet configured to prevent duplicate rules
        $configuredTypes = ProgressActivity::pluck('activity_type')->toArray();
        $availableTypes = array_filter(self::$activityTypes, function($key) use ($configuredTypes) {
            return !in_array($key, $configuredTypes);
        }, ARRAY_FILTER_USE_KEY);

        return view('admin.progress-tracking.create', compact('availableTypes'));
    }

    /**
     * Store a newly created rule.
     */
    public function store(Request $request)
    {
        $request->validate([
            'activity_type' => 'required|string|unique:progress_activities,activity_type',
            'percentage' => 'required|integer|min:1',
            'max_limit' => 'required|integer|min:1',
        ]);

        $title = self::$activityTypes[$request->activity_type] ?? ucfirst(str_replace('_', ' ', $request->activity_type));

        ProgressActivity::create([
            'activity_type' => $request->activity_type,
            'title' => $title,
            'percentage' => $request->percentage,
            'max_limit' => $request->max_limit,
        ]);

        return redirect()->route('admin.progress-tracking.index')->with('success', 'Progress activity configured successfully.');
    }

    /**
     * Show the form for editing the rule.
     */
    public function edit(ProgressActivity $progressActivity)
    {
        $activityName = self::$activityTypes[$progressActivity->activity_type] ?? $progressActivity->title;
        return view('admin.progress-tracking.edit', compact('progressActivity', 'activityName'));
    }

    /**
     * Update the rule.
     */
    public function update(Request $request, ProgressActivity $progressActivity)
    {
        $request->validate([
            'percentage' => 'required|integer|min:1',
            'max_limit' => 'required|integer|min:1',
        ]);

        $progressActivity->update([
            'percentage' => $request->percentage,
            'max_limit' => $request->max_limit,
        ]);

        return redirect()->route('admin.progress-tracking.index')->with('success', 'Progress activity updated successfully.');
    }

    /**
     * Remove the rule.
     */
    public function destroy(ProgressActivity $progressActivity)
    {
        $progressActivity->delete();
        return redirect()->route('admin.progress-tracking.index')->with('success', 'Progress activity removed successfully.');
    }
}
