<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index(){
        return view('welcome');
    }

    public function tac()
    {
        return view('tac');
    }

    public function exportTableDataAndSchema($mysqlHost, $mysqlPath,$mysqlUserName, $mysqlPassword, $mysqlDatabaseName, $table, $outputFile, $end_date)
    {
        // Ensure the file path is a valid path
        $filePath = escapeshellarg($outputFile);
    
        // Build the command string
        $command = "$mysqlPath --host=$mysqlHost --user=$mysqlUserName --password=$mysqlPassword $mysqlDatabaseName $table --where='id < $end_date'> $outputFile";

        // Execute the command
        exec($command, $output, $return_var);

        // Check for success
        if ($return_var !== 0) {
            throw new Exception("mysqldump failed with return code $return_var. Output: " . implode("\n", $output));
            // throw new Exception("mysqldump failed with return code $return_var. Output: " . implode("\n", $output));
        }
        
        return $filePath;
    }   

    // public function dumpData(Request $request){
    //     // Example usage
    //     $outputFile = 'exported_data_and_schema.sql';

    //     $table = $request->table;
    //     $end_date = $request->end_date;
    //     $mysqlUserName = env('DB_USERNAME');
    //     $mysqlPassword = env('DB_PASSWORD');
    //     $mysqlDatabaseName = env('DB_DATABASE');
    //     $mysqlPath = '/Applications/MAMP/Library/bin/mysqldump';
    //     $mysqlHost = 'localhost';

    //     try {
    //         //code...
    //         dd($this->exportTableDataAndSchema($mysqlHost, $mysqlPath,$mysqlUserName, $mysqlPassword, $mysqlDatabaseName, $table, $outputFile, $end_date));
    //     } catch (\Throwable $th) {
    //         //throw $th;
    //         dd($th->getMessage());
    //     }
    // }

}
