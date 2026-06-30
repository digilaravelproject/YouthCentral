<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $areas = Area::with(['city', 'city.state'])->paginate(10);
        
        // For AJAX requests (infinite scroll), return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $areas->items(),
                'pagination' => [
                    'current_page' => $areas->currentPage(),
                    'last_page' => $areas->lastPage(),
                    'has_more' => $areas->hasMorePages(),
                    'total' => $areas->total()
                ]
            ]);
        }
        
        return view('admin.location.areas.index', compact('areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cities = City::with('state')->get();
        return view('admin.location.areas.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
        ]);

        Area::create([
            'name' => $request->name,
            'city_id' => $request->city_id,
        ]);

        return redirect()->route('admin.areas.index')
            ->with('success', 'Area created successfully');
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
    public function edit(Area $area)
    {
        $cities = City::with('state')->get();
        return view('admin.location.areas.edit', compact('area', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Area $area)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
        ]);

        $area->update([
            'name' => $request->name,
            'city_id' => $request->city_id,
        ]);

        return redirect()->route('admin.areas.index')
            ->with('success', 'Area updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {
        // Here you might want to check if any businesses reference this area
        // and prevent deletion if needed.
        
        $area->delete();

        return redirect()->route('admin.areas.index')
            ->with('success', 'Area deleted successfully');
    }

    public function bulkDestroy(Request $request)
    {
        $idsJson = $request->input('ids', '[]');
        $ids = json_decode($idsJson, true);
        if (!is_array($ids)) {
            $ids = [];
        }
        
        $deletedCount = 0;
        foreach ($ids as $id) {
            $area = Area::find($id);
            if ($area) {
                $area->delete();
                $deletedCount++;
            }
        }
        
        return redirect()->route('admin.areas.index')
            ->with('success', "Deleted $deletedCount areas successfully.");
    }

    public function downloadSample()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'State Name');
        $sheet->setCellValue('B1', 'City Name');
        $sheet->setCellValue('C1', 'Area Name');
        $sheet->setCellValue('A2', 'Maharashtra');
        $sheet->setCellValue('B2', 'Mumbai');
        $sheet->setCellValue('C2', 'Andheri');
        $sheet->setCellValue('A3', 'Delhi');
        $sheet->setCellValue('B3', 'New Delhi');
        $sheet->setCellValue('C3', 'Connaught Place');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });
        
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="areas_sample.xlsx"');
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
                $areaName = isset($row[2]) ? trim($row[2]) : '';
                if ($stateName === '' || $cityName === '' || $areaName === '') continue;

                $state = \App\Models\State::firstOrCreate([
                    'name' => $stateName
                ]);

                $city = City::firstOrCreate([
                    'name' => $cityName,
                    'state_id' => $state->id
                ], [
                    'slug' => \Illuminate\Support\Str::slug($cityName)
                ]);

                Area::firstOrCreate([
                    'name' => $areaName,
                    'city_id' => $city->id
                ]);
                $importedCount++;
            }

            return redirect()->route('admin.areas.index')
                ->with('success', "$importedCount areas imported successfully.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }
}
