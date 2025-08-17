<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Sheets;
use Revolution\Google\Sheets\Sheets as GoogleSheets;
// use Sheets;

class GoogleSheetController extends Controller
{
    public function index()
    {

        $client = new Client();
        $client->setScopes([Sheets::DRIVE, Sheets::SPREADSHEETS]);
        // setup Google Client
        // ...

        $service = new Sheets($client);

       $sheets = new GoogleSheets();
        $sheets->setService($service);
dd($sheets);

        /*
            Get Google Sheet Data....
        */

            // $sheets = Sheets::spreadsheet('1wOzbJ7BbYvvIHodhDQXXqRutijWmOEUXUc5JNt6gofY')->sheet('sheet2')->get();

            // $header = $sheets->pull(0);
            // $values = Sheets::collection(header: $header, rows: $sheets);
            // $data = array_values($values->toArray());

            // dd($data);

        /*
            Add New Google Sheet....
        */
            // Sheets::spreadsheetByTitle('Email_Marketing_Websites')->addSheet('New Sheet Title');

            // $add_sheets = Sheets::spreadsheet('1wOzbJ7BbYvvIHodhDQXXqRutijWmOEUXUc5JNt6gofY')->addSheet('sheet2');

            // $add_sheets->spreadsheetId;

            // $row = [

            //     ['Id', 'Name', 'Email'],
            //     ['1', 'a', 'a@gmail.com'],
            //     ['2', 'b', 'b@gmail.com'],
            //     ['3', 'c', 'c@gmail.com'],
            // ];

            
            // $sheet2 = Sheets::spreadsheet('1wOzbJ7BbYvvIHodhDQXXqRutijWmOEUXUc5JNt6gofY')->sheet('sheet2')->append($row);

            // dd($sheet2);

        /*
            Delete New Google Sheet....
        */

            // $add_sheets = Sheets::spreadsheet('1wOzbJ7BbYvvIHodhDQXXqRutijWmOEUXUc5JNt6gofY')->deleteSheet('sheet2');
    }
}
