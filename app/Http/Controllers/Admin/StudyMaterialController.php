<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudyMaterial;
use App\Models\Student;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Notifications\StudyMaterialUploaded;
use App\Models\Student as StudentModel;

class StudyMaterialController extends Controller
{
    public function index()
    {
        $materials = StudyMaterial::latest()->paginate(10);
        return view('admin.study-materials.index', compact('materials'));
    }

    public function create()
    {
        $classes = ['5th', '6th', '7th', '8th', '9th'];
        return view('admin.study-materials.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:PDF,Worksheet,STEM',
            'subject' => 'nullable|string|max:255',
            'month' => 'nullable|string',
            'topic' => 'nullable|string|max:255',
            'file' => 'required_if:type,PDF,Worksheet|file|mimes:pdf,doc,docx,zip|max:10240',
            'video_link' => 'required_if:type,STEM|nullable|url',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:event_registrations,id',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only(['title', 'type', 'video_link', 'subject', 'topic']);
            // month input from type="month" comes as YYYY-MM, convert to date
            if ($request->filled('month')) {
                $monthVal = $request->input('month');
                if (preg_match('/^\d{4}-\d{2}$/', $monthVal)) {
                    $data['month'] = $monthVal . '-01';
                } else {
                    $data['month'] = $monthVal;
                }
            }
            
            if ($request->hasFile('file')) {
                $data['file_path'] = $request->file('file')->store('study-materials', 'public');
            }

            $material = StudyMaterial::create($data);
            $material->students()->attach($request->student_ids);

            // Notify assigned students (by event registration ids -> find student records)
            $students = StudentModel::whereIn('event_registration_id', $request->student_ids)->get();
            foreach ($students as $student) {
                try {
                    $student->notify(new StudyMaterialUploaded($material));
                } catch (\Exception $e) {
                    // swallow notification exceptions to avoid breaking upload
                }
            }

            DB::commit();
            return redirect()->route('admin.study-materials.index')->with('success', 'Study material uploaded and assigned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }

    public function show(StudyMaterial $studyMaterial)
    {
        $studyMaterial->load('students');
        return view('admin.study-materials.show', compact('studyMaterial'));
    }

    public function edit(StudyMaterial $studyMaterial)
    {
        $classes = ['5th', '6th', '7th', '8th', '9th'];
        $assignedIds = $studyMaterial->students()->pluck('event_registrations.id')->toArray();
        // try to detect class if all assigned students belong to same grade
        $grades = [];
        $assignedStudents = [];
        if (!empty($assignedIds)) {
            $grades = EventRegistration::whereIn('id', $assignedIds)->pluck('grade')->unique()->values()->toArray();
            $assignedStudents = EventRegistration::whereIn('id', $assignedIds)->get(['id','first_name','last_name','mobile','grade'])->toArray();
        }
        $selectedClass = count($grades) === 1 ? $grades[0] : null;
        return view('admin.study-materials.edit', compact('studyMaterial', 'classes', 'assignedIds', 'selectedClass', 'assignedStudents'));
    }

    public function update(Request $request, StudyMaterial $studyMaterial)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:PDF,Worksheet,STEM',
            'subject' => 'nullable|string|max:255',
            'month' => 'nullable|string',
            'topic' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,zip|max:10240',
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
                // delete old file
                if ($studyMaterial->file_path) {
                    Storage::disk('public')->delete($studyMaterial->file_path);
                }
                $data['file_path'] = $request->file('file')->store('study-materials', 'public');
            }

            $oldIds = $studyMaterial->students()->pluck('event_registrations.id')->toArray();
            $studyMaterial->update($data);
            $studyMaterial->students()->sync($request->student_ids);

            // notify newly added students
            $newIds = array_diff($request->student_ids, $oldIds);
            if (!empty($newIds)) {
                $students = StudentModel::whereIn('event_registration_id', $newIds)->get();
                foreach ($students as $student) {
                    try {
                        $student->notify(new StudyMaterialUploaded($studyMaterial));
                    } catch (\Exception $e) {
                        // ignore
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.study-materials.index')->with('success', 'Study material updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(StudyMaterial $studyMaterial)
    {
        if ($studyMaterial->file_path) {
            Storage::disk('public')->delete($studyMaterial->file_path);
        }
        $studyMaterial->delete();
        return redirect()->route('admin.study-materials.index')->with('success', 'Study material deleted successfully.');
    }

    public function toggleStatus(StudyMaterial $studyMaterial)
    {
        $studyMaterial->update(['status' => !$studyMaterial->status]);
        return back()->with('success', 'Status updated successfully.');
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
