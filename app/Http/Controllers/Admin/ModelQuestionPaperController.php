<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\ModelQuestionPaper;
use App\Models\EventRegistration;
use App\Models\Student as StudentModel;
use App\Notifications\ModelQuestionPaperUploaded;

class ModelQuestionPaperController extends Controller
{
    public function index()
    {
        $papers = ModelQuestionPaper::latest()->paginate(10);
        return view('admin.model-question-papers.index', compact('papers'));
    }

    public function create()
    {
        $classes = ['5th', '6th', '7th', '8th', '9th'];
        return view('admin.model-question-papers.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:PDF,Worksheet,STEM',
            'subject' => 'nullable|string|max:255',
            'month' => 'nullable|string',
            'topic' => 'nullable|string|max:255',
            'file' => 'required_if:type,PDF,Worksheet|file|mimes:pdf,doc,docx,zip,xls,xlsx|max:10240',
            'video_link' => 'required_if:type,STEM|nullable|url',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:event_registrations,id',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only(['title', 'type', 'video_link', 'subject', 'topic']);
            if ($request->filled('month')) {
                $monthVal = $request->input('month');
                if (preg_match('/^\d{4}-\d{2}$/', $monthVal)) {
                    $data['month'] = $monthVal . '-01';
                } else {
                    $data['month'] = $monthVal;
                }
            }

            if ($request->hasFile('file')) {
                $data['file_path'] = $request->file('file')->store('model-question-papers', 'public');
            }

            $paper = ModelQuestionPaper::create($data);
            $paper->students()->attach($request->student_ids);

            // Notify assigned students
            $students = StudentModel::whereIn('event_registration_id', $request->student_ids)->get();
            foreach ($students as $student) {
                try {
                    $student->notify(new ModelQuestionPaperUploaded($paper));
                } catch (\Exception $e) {
                    // ignore
                }
            }

            DB::commit();
            return redirect()->route('admin.model-question-papers.index')->with('success', 'Model question paper uploaded and assigned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }

    public function show(ModelQuestionPaper $modelQuestionPaper)
    {
        $modelQuestionPaper->load('students');
        return view('admin.model-question-papers.show', ['paper' => $modelQuestionPaper]);
    }

    public function edit(ModelQuestionPaper $modelQuestionPaper)
    {
        $classes = ['5th', '6th', '7th', '8th', '9th'];
        // Fetch assigned event_registration IDs directly from the pivot table to avoid
        // any ambiguity with table aliases when plucking via the relationship.
        $assignedIds = DB::table('model_question_paper_assignments')
            ->where('model_question_paper_id', $modelQuestionPaper->id)
            ->pluck('event_registration_id')
            ->toArray();

        $grades = [];
        $assignedStudents = [];
        if (!empty($assignedIds)) {
            $grades = EventRegistration::whereIn('id', $assignedIds)->pluck('grade')->unique()->values()->toArray();
            $assignedStudents = EventRegistration::whereIn('id', $assignedIds)->get(['id','first_name','last_name','mobile','grade'])->toArray();
        }
        $selectedClass = count($grades) === 1 ? $grades[0] : null;
        return view('admin.model-question-papers.edit', compact('modelQuestionPaper', 'classes', 'assignedIds', 'selectedClass', 'assignedStudents'));
    }

    public function update(Request $request, ModelQuestionPaper $modelQuestionPaper)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:PDF,Worksheet,STEM',
            'subject' => 'nullable|string|max:255',
            'month' => 'nullable|string',
            'topic' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,zip,xls,xlsx|max:10240',
            'video_link' => 'required_if:type,STEM|nullable|url',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:event_registrations,id',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only(['title', 'type', 'video_link', 'subject', 'topic']);
            if ($request->filled('month')) {
                $monthVal = $request->input('month');
                if (preg_match('/^\d{4}-\d{2}$/', $monthVal)) {
                    $data['month'] = $monthVal . '-01';
                } else {
                    $data['month'] = $monthVal;
                }
            } else {
                $data['month'] = null;
            }

            if ($request->hasFile('file')) {
                if ($modelQuestionPaper->file_path) {
                    Storage::disk('public')->delete($modelQuestionPaper->file_path);
                }
                $data['file_path'] = $request->file('file')->store('model-question-papers', 'public');
            }

            $oldIds = $modelQuestionPaper->students()->pluck('event_registrations.id')->toArray();
            $modelQuestionPaper->update($data);
            $modelQuestionPaper->students()->sync($request->student_ids);

            $newIds = array_diff($request->student_ids, $oldIds);
            if (!empty($newIds)) {
                $students = StudentModel::whereIn('event_registration_id', $newIds)->get();
                foreach ($students as $student) {
                    try {
                        $student->notify(new ModelQuestionPaperUploaded($modelQuestionPaper));
                    } catch (\Exception $e) {
                        // ignore
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.model-question-papers.index')->with('success', 'Model question paper updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(ModelQuestionPaper $modelQuestionPaper)
    {
        if ($modelQuestionPaper->file_path) {
            Storage::disk('public')->delete($modelQuestionPaper->file_path);
        }
        $modelQuestionPaper->delete();
        return redirect()->route('admin.model-question-papers.index')->with('success', 'Model question paper deleted successfully.');
    }

    public function getStudents(Request $request)
    {
        try {
            $students = EventRegistration::where('grade', $request->class)
                ->get(['id', 'first_name', 'last_name', 'mobile', 'grade']);

            return response()->json($students);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
