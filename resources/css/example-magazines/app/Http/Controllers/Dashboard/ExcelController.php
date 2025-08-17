<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\ExcelExport;
use App\Imports\ExcelImport;
use Excel;

class ExcelController extends Controller
{
    public function excel()
    {
        return view('dashboard.excel.view');
    }

    public function index(Request $request) 
    {
        if (request()->method() === 'GET') {
            return Excel::download(new ExcelExport, 'excel.xlsx');
        }

        $Validator = $request->validate([

            'excel_sheet' => 'required|file|mimes:xls,xlsx',

        ]);

        $import = Excel::import(new ExcelImport, $request->excel_sheet);

        if ($import) {
            return redirect()->back()->with('success', 'Data inserted successfully');
        }

        return redirect()->back()->with('error', 'Please Try Again !!');

    }
}
