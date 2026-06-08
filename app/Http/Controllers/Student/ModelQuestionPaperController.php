<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ModelQuestionPaper;

class ModelQuestionPaperController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();
        $allRegs = \App\Models\EventRegistration::where('mobile', $student->phone)->get(['id', 'grade']);
        $availableClasses = $allRegs->pluck('grade')->filter()->unique()->values();
        $selectedClass = request('class');
        if ($selectedClass) {
            $registrationIds = $allRegs->where('grade', $selectedClass)->pluck('id');
        } else {
            $registrationIds = $allRegs->pluck('id');
        }

        $query = ModelQuestionPaper::whereHas('students', function ($q) use ($registrationIds) {
            $q->whereIn('event_registration_id', $registrationIds);
        })->where('status', true);

        // Search
        if (request()->filled('q')) {
            $qterm = request('q');
            $query->where(function($r) use ($qterm) {
                $r->where('title', 'like', "%{$qterm}%")
                  ->orWhere('subject', 'like', "%{$qterm}%")
                  ->orWhere('topic', 'like', "%{$qterm}%");
            });
        }

        if (request()->filled('subject')) {
            $query->where('subject', request('subject'));
        }
        if (request()->filled('topic')) {
            $query->where('topic', request('topic'));
        }
        if (request()->filled('month')) {
            $month = request('month');
            if (preg_match('/^\d{4}-\d{2}$/', $month)) {
                $query->whereMonth('month', date('m', strtotime($month . '-01')))
                      ->whereYear('month', date('Y', strtotime($month . '-01')));
            }
        }

        $papers = $query->latest()->paginate(10)->withQueryString();

        $accessible = ModelQuestionPaper::whereHas('students', function ($q) use ($registrationIds) {
            $q->whereIn('event_registration_id', $registrationIds);
        })->where('status', true);

        $subjects = $accessible->pluck('subject')->filter()->unique()->values();
        $topics = $accessible->pluck('topic')->filter()->unique()->values();
        $months = $accessible->pluck('month')->filter()->unique()->values();

        // Build completed map for the current registration (prefer selected class)
        if ($selectedClass) {
            $registration = \App\Models\EventRegistration::where('mobile', $student->phone)->where('grade', $selectedClass)->first();
        } else {
            $registration = \App\Models\EventRegistration::where('mobile', $student->phone)->first();
        }
        $completedMap = [];
        if ($registration) {
            $paperIds = $papers->pluck('id')->toArray();
            $assignments = \DB::table('model_question_paper_assignments')
                ->whereIn('model_question_paper_id', $paperIds)
                ->where('event_registration_id', $registration->id)
                ->get(['model_question_paper_id', 'completed_at']);
            foreach ($assignments as $a) {
                $completedMap[$a->model_question_paper_id] = $a->completed_at ? true : false;
            }
        }

        return view('student.model-question-papers.index', compact('papers', 'subjects', 'topics', 'months', 'completedMap', 'availableClasses', 'selectedClass'));
    }

    public function download(ModelQuestionPaper $modelQuestionPaper)
    {
        $student = Auth::guard('student')->user();
        $registrationIds = \App\Models\EventRegistration::where('mobile', $student->phone)->pluck('id');
        $assigned = $modelQuestionPaper->students()->whereIn('event_registration_id', $registrationIds)->exists();
        if (! $assigned) {
            abort(403, 'You do not have access to this model question paper.');
        }

        // Limit Check for Model Question Papers Downloads
        $progressService = app(\App\Services\StudentProgressService::class);
        $check = $progressService->checkLimitAndLog($student, 'model_question_paper_download', $modelQuestionPaper->id);

        if (!$check['allowed']) {
            return redirect()->back()->with('error', $check['message']);
        }

        if (! $modelQuestionPaper->file_path) {
            return redirect()->back()->with('error', 'No file available for download.');
        }

        $storagePath = storage_path('app/public/' . $modelQuestionPaper->file_path);
        if (! file_exists($storagePath)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        $downloadName = basename($modelQuestionPaper->file_path);
        return response()->download($storagePath, $downloadName);
    }

    /**
     * View/Watch a model question paper file/video if the authenticated student has access.
     */
    public function view(ModelQuestionPaper $modelQuestionPaper)
    {
        $student = Auth::guard('student')->user();

        // Find registration IDs for this student
        $registrationIds = \App\Models\EventRegistration::where('mobile', $student->phone)
            ->pluck('id');

        // Check that this paper is assigned to at least one of the student's registrations
        $assigned = $modelQuestionPaper->students()->whereIn('event_registration_id', $registrationIds)->exists();
        if (! $assigned) {
            abort(403, 'You do not have access to this model question paper.');
        }

        // Limit Check for Model Question Papers View
        $progressService = app(\App\Services\StudentProgressService::class);
        $check = $progressService->checkLimitAndLog($student, 'model_question_paper_view', $modelQuestionPaper->id);

        if (!$check['allowed']) {
            return redirect()->back()->with('error', $check['message']);
        }

        // Redirect to Youtube/Video link or storage file
        if ($modelQuestionPaper->type === 'STEM' && $modelQuestionPaper->video_link) {
            return redirect()->away($modelQuestionPaper->video_link);
        }

        if ($modelQuestionPaper->file_path) {
            return redirect()->away(asset('storage/' . $modelQuestionPaper->file_path));
        }

        return redirect()->back()->with('error', 'Model question paper has no viewable content.');
    }

    public function markCompleted(ModelQuestionPaper $modelQuestionPaper)
    {
        $student = Auth::guard('student')->user();
        $selectedClass = request('class');
        if ($selectedClass) {
            $registration = \App\Models\EventRegistration::where('mobile', $student->phone)->where('grade', $selectedClass)->first();
        } else {
            $registration = \App\Models\EventRegistration::where('mobile', $student->phone)->first();
        }
        if (! $registration) abort(403);

        $assignment = \DB::table('model_question_paper_assignments')
            ->where('model_question_paper_id', $modelQuestionPaper->id)
            ->where('event_registration_id', $registration->id)
            ->first();

        if (! $assignment) abort(403);

        // Limit Check for Paper Completed
        $progressService = app(\App\Services\StudentProgressService::class);
        $check = $progressService->checkLimitAndLog($student, 'paper_completed', $modelQuestionPaper->id);

        if (!$check['allowed']) {
            return redirect()->back()->with('error', $check['message']);
        }

        \DB::table('model_question_paper_assignments')
            ->where('id', $assignment->id)
            ->update(['completed_at' => now()]);

        return back()->with('success', 'Marked as completed.');
    }
}
