<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudyMaterialController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();
        // Find all registrations for this student (they may have multiple grades over time)
        $allRegs = \App\Models\EventRegistration::where('mobile', $student->phone)->get(['id', 'grade']);
        $availableClasses = $allRegs->pluck('grade')->filter()->unique()->values();

        // If class filter selected, limit to registrations for that grade, else use all
        $selectedClass = request('class');
        if ($selectedClass) {
            $registrationIds = $allRegs->where('grade', $selectedClass)->pluck('id');
        } else {
            $registrationIds = $allRegs->pluck('id');
        }

        $query = \App\Models\StudyMaterial::whereHas('students', function ($q) use ($registrationIds) {
                $q->whereIn('event_registration_id', $registrationIds);
            })->where('status', true);

        // Filters
        if (request()->filled('subject')) {
            $query->where('subject', request('subject'));
        }
        if (request()->filled('topic')) {
            $query->where('topic', request('topic'));
        }
        if (request()->filled('month')) {
            // month expected as YYYY-MM
            $month = request('month');
            if (preg_match('/^\d{4}-\d{2}$/', $month)) {
                $query->whereMonth('month', date('m', strtotime($month . '-01')))
                      ->whereYear('month', date('Y', strtotime($month . '-01')));
            }
        }

        $materials = $query->latest()->paginate(12)->withQueryString();

        // For filter dropdowns, fetch available subjects, topics and months from accessible materials
        $accessible = \App\Models\StudyMaterial::whereHas('students', function ($q) use ($registrationIds) {
            $q->whereIn('event_registration_id', $registrationIds);
        })->where('status', true);

        $subjects = $accessible->pluck('subject')->filter()->unique()->values();
        $topics = $accessible->pluck('topic')->filter()->unique()->values();
        $months = $accessible->pluck('month')->filter()->unique()->values();

        return view('student.study-materials.index', compact('materials', 'subjects', 'topics', 'months', 'availableClasses', 'selectedClass'));
    }

    /**
     * Download a study material file if the authenticated student has access.
     */
    public function download(\App\Models\StudyMaterial $studyMaterial)
    {
        $student = Auth::guard('student')->user();

        // Find registration IDs for this student
        $registrationIds = \App\Models\EventRegistration::where('mobile', $student->phone)
            ->pluck('id');

        // Check that this study material is assigned to at least one of the student's registrations
        $assigned = $studyMaterial->students()->whereIn('event_registration_id', $registrationIds)->exists();
        if (! $assigned) {
            abort(403, 'You do not have access to this study material.');
        }

        // Limit Check for Study Material Download
        $progressService = app(\App\Services\StudentProgressService::class);
        $check = $progressService->checkLimitAndLog($student, 'study_material_download', $studyMaterial->id);

        if (!$check['allowed']) {
            return redirect()->back()->with('error', $check['message']);
        }

        if (! $studyMaterial->file_path) {
            return redirect()->back()->with('error', 'No file available for download.');
        }

        $storagePath = storage_path('app/public/' . $studyMaterial->file_path);
        if (! file_exists($storagePath)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        $downloadName = basename($studyMaterial->file_path);
        return response()->download($storagePath, $downloadName);
    }

    /**
     * View/Watch a study material file/video if the authenticated student has access.
     */
    public function view(\App\Models\StudyMaterial $studyMaterial)
    {
        $student = Auth::guard('student')->user();

        // Find registration IDs for this student
        $registrationIds = \App\Models\EventRegistration::where('mobile', $student->phone)
            ->pluck('id');

        // Check that this study material is assigned to at least one of the student's registrations
        $assigned = $studyMaterial->students()->whereIn('event_registration_id', $registrationIds)->exists();
        if (! $assigned) {
            abort(403, 'You do not have access to this study material.');
        }

        // Limit Check for Study Material View
        $progressService = app(\App\Services\StudentProgressService::class);
        $check = $progressService->checkLimitAndLog($student, 'study_material_view', $studyMaterial->id);

        if (!$check['allowed']) {
            return redirect()->back()->with('error', $check['message']);
        }

        // Redirect to Youtube/Video link or storage file
        if ($studyMaterial->type === 'Video' && $studyMaterial->video_link) {
            return redirect()->away($studyMaterial->video_link);
        }

        if ($studyMaterial->file_path) {
            return redirect()->away(asset('storage/' . $studyMaterial->file_path));
        }

        return redirect()->back()->with('error', 'Study material has no viewable content.');
    }
}
