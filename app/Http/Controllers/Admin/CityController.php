<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cities = City::with('state')->withCount('areas')->paginate(10);
        
        // For AJAX requests (infinite scroll), return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $cities->items(),
                'pagination' => [
                    'current_page' => $cities->currentPage(),
                    'last_page' => $cities->lastPage(),
                    'has_more' => $cities->hasMorePages(),
                    'total' => $cities->total()
                ]
            ]);
        }
        
        return view('admin.location.cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $states = State::all();
        return view('admin.location.cities.create', compact('states'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
        ]);

        City::create([
            'name' => $request->name,
            'state_id' => $request->state_id,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.cities.index')
            ->with('success', 'City created successfully');
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
    public function edit(City $city)
    {
        $states = State::all();
        return view('admin.location.cities.edit', compact('city', 'states'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
        ]);

        $city->update([
            'name' => $request->name,
            'state_id' => $request->state_id,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.cities.index')
            ->with('success', 'City updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        // Check if the city has areas
        if ($city->areas()->count() > 0) {
            return redirect()->route('admin.cities.index')
                ->with('error', 'Cannot delete city because it has areas.');
        }

        $city->delete();

        return redirect()->route('admin.cities.index')
            ->with('success', 'City deleted successfully');
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
            $city = City::find($id);
            if ($city) {
                if ($city->areas()->count() > 0) {
                    $skippedCount++;
                } else {
                    $city->delete();
                    $deletedCount++;
                }
            }
        }
        
        if ($skippedCount > 0) {
            return redirect()->route('admin.cities.index')
                ->with('success', "Deleted $deletedCount cities. $skippedCount cities skipped because they have areas.");
        }
        return redirect()->route('admin.cities.index')
            ->with('success', "Deleted $deletedCount cities successfully.");
    }

    public function downloadSample()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'State Name');
        $sheet->setCellValue('B1', 'City Name');
        $sheet->setCellValue('A2', 'Maharashtra');
        $sheet->setCellValue('B2', 'Mumbai');
        $sheet->setCellValue('A3', 'Delhi');
        $sheet->setCellValue('B3', 'New Delhi');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });
        
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="cities_sample.xlsx"');
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
                $cityName = isset($row[1]) ? trim($row[1]) : '';
                if ($stateName === '' || $cityName === '') continue;

                $state = State::firstOrCreate([
                    'name' => $stateName
                ]);

                City::firstOrCreate([
                    'name' => $cityName,
                    'state_id' => $state->id
                ], [
                    'slug' => Str::slug($cityName)
                ]);
                $importedCount++;
            }

            return redirect()->route('admin.cities.index')
                ->with('success', "$importedCount cities imported successfully.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }
}
