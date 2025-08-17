<?php

namespace App\Exports;

use App\Models\User;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;

// class ExcelExport implements FromCollection
class ExcelExport implements FromQuery, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

     use Exportable;

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        // Define your data query here
        return User::select('username', 'email', 'created_at');
    }

    public function map($user): array
    {
        return [
            $user->username,
            $user->email,
            $user->created_at,
        ];
    }

    // public function collection()
    // {
    //     return User::all();
    // }
}

/*
    Add Multiple Tables

    ================
    ================

    $user_login = DB::table('user_logins')->get();
        $packages = DB::table('packages')->get();
        $articles = DB::table('articles')->get();
        $images = DB::table('images')->get();

        $data = [];

        // User Data
        $data[] = [
            'Username',
            'Email',
            'Created At',
        ];

        $data[] = [
            $user->username,
            $user->email,
            $user->created_at,
        ];

        // User Login Data
        $data[] = [
            'User Login id',
            // Add additional headings if needed
        ];

        foreach ($user_login as $login) {
            $data[] = [
                $login->id,
            ];
        }

        // Packages Data
        $data[] = [
            'Packages id',
            // Add additional headings if needed
        ];

        foreach ($packages as $package) {
            $data[] = [
                $package->id,
            ];
        }

        // Articles Data
        $data[] = [
            'Articles id',
            // Add additional headings if needed
        ];

        foreach ($articles as $article) {
            $data[] = [
                $article->id,
            ];
        }

        // Images Data
        $data[] = [
            'Images id',
            // Add additional headings if needed
        ];

        foreach ($images as $image) {
            $data[] = [
                $image->image,
            ];
        }

        return $data;

*/