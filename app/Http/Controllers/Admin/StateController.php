<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $states = State::withCount('cities')->paginate(10);
        return view('admin.location.states.index', compact('states'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.location.states.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:states',
        ]);

        State::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.states.index')
            ->with('success', 'State created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(State $state)
    {
        return view('admin.location.states.edit', compact('state'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, State $state)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:states,name,' . $state->id,
        ]);

        $state->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.states.index')
            ->with('success', 'State updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(State $state)
    {
        // Check if the state has cities
        if ($state->cities()->count() > 0) {
            return redirect()->route('admin.states.index')
                ->with('error', 'Cannot delete state because it has cities.');
        }

        $state->delete();

        return redirect()->route('admin.states.index')
            ->with('success', 'State deleted successfully');
    }

    public function bulkDestroy(Request $request)
    {
        $idsJson = $request->input('ids', '[]');
        $ids = json_decode($idsJson, true);
        if (!is_array($ids)) {
            $ids = [];
        }
        
        $deletedCount = 0;
        $skippedCount = 0;
        foreach ($ids as $id) {
            $state = State::find($id);
            if ($state) {
                if ($state->cities()->count() > 0) {
                    $skippedCount++;
                } else {
                    $state->delete();
                    $deletedCount++;
                }
            }
        }
        
        if ($skippedCount > 0) {
            return redirect()->route('admin.states.index')
                ->with('success', "Deleted $deletedCount states. $skippedCount states skipped because they have cities.");
        }
        return redirect()->route('admin.states.index')
            ->with('success', "Deleted $deletedCount states successfully.");
    }

    public function downloadSample()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'State Name');
        $sheet->setCellValue('A2', 'Maharashtra');
        $sheet->setCellValue('A3', 'Delhi');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });
        
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="states_sample.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');
        
        return $response;
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            
            if (count($rows) <= 1) {
                return redirect()->back()->with('error', 'The spreadsheet is empty.');
            }

            $importedCount = 0;
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                $stateName = isset($row[0]) ? trim($row[0]) : '';
                if ($stateName === '') continue;

                State::firstOrCreate([
                    'name' => $stateName
                ]);
                $importedCount++;
            }

            return redirect()->route('admin.states.index')
                ->with('success', "$importedCount states imported successfully.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }
}
