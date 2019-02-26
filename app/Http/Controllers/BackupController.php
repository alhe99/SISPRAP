<?php

namespace App\Http\Controllers;

use Log;
use Artisan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function backup()
    {
       /*  $now = Carbon::now();
        try {
            Artisan::call('db:backup',[ 
                '--destination' => 'local',
                '--destinationPath' => $now->toDateTimeString(). ' -db-sisprap.sql',
                '--compression' => null
            ]);

            Artisan::output();
        } catch(\Exception $e) {
            return Response::json([
                'success' => false,
                'errors' => ""
            ], 400);
        }
        return Response::json([
            'success' => true,
            'message' => 'success'
        ]); */
        $exitCode = Artisan::call('db:backup');
        
    }
}
