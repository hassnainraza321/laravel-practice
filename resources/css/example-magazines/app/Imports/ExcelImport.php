<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

// class ExcelImport implements WithMappedCells, ToModel 
class ExcelImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    // public function mapping(): array
    // {
    //     return [
    //         'username'  => 'A1',
    //         'email' => 'A2',
    //     ];
    // }
    
    public function model(array $row)
    {
        return new User([
           'username'     => $row[0],
           'email'    => $row[1],
           'password' => bcrypt($row[2]),
        ]);
    }
}
