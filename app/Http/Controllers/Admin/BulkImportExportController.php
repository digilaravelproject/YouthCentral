<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Business;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\State;
use App\Models\City;
use App\Models\Area;
use App\Imports\BusinessesImport;
use App\Exports\BusinessesExport;

class BulkImportExportController extends Controller
{
    public function index()
    {
        return view('admin.bulk-import-export.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls|max:10240'
        ]);

        try {
            $import = new BusinessesImport;
            Excel::import($import, $request->file('file'));
            
            // Get import statistics
            $stats = $import->getImportStats();
            
            // Prepare success message with statistics
            $message = "Import completed! ";
            $message .= "Successfully imported: {$stats['success_count']} businesses. ";
            
            if ($stats['skip_count'] > 0) {
                $message .= "Skipped: {$stats['skip_count']} rows. ";
            }
            
            if ($stats['error_count'] > 0) {
                $message .= "Errors: {$stats['error_count']} rows. ";
                
                // Store errors in session for detailed view
                session()->flash('import_errors', $stats['errors']);
                session()->flash('import_stats', $stats);
                
                return redirect()->back()
                    ->with('warning', $message)
                    ->with('show_errors', true);
            }
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            
            foreach ($failures as $failure) {
                $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            }
            
            return redirect()->back()
                ->with('error', 'Validation failed during import.')
                ->with('validation_errors', $errorMessages);
                
        } catch (\Exception $e) {
            \Log::error('Import failed with exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->back()->with('error', 'Error importing data: ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new BusinessesExport, 'businesses.xlsx');
    }

    public function downloadSample()
    {
        return response()->download(public_path('samples/business_import_sample.xlsx'));
    }
} 