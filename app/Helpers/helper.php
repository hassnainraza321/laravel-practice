<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Carbon\Carbon;
use DB;
use App\Models\User;

class Helper
{
	public static function getDatatables($table_data, $with_select_all = 1, $class = 'nowrap')
    {
        $table = '<div class="table-responsive">
                    <table class="table w-100 datatable '. $class .'">
                        <thead>
                            <tr>';

        if (!empty($with_select_all))
        {
            $table .= '<th>
                            <div class="form-check form-checkbox-dark">
                                <input type="checkbox" class="form-check-input select-all-checkbox" id="select-all-checkbox">
                                <label class="form-check-label" for="select-all-checkbox">&nbsp;</label>
                            </div>
                        </th>';
        }

        foreach ($table_data as $key => $th)
        {
            $table .= '<th>' . __($th) . '</th>';
        }

        $total_columns = count($table_data);

        if (!empty($with_select_all))
        {
            $total_columns++;
        }

        $table .= '</tr>
                </thead>
                <tbody><tr colspan="'. $total_columns .'" class="text-center"><td>Loading...</td></tr></tbody>
            </table>
        </div>';

        return $table;
    }
}