<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;
use Log;

class BackupController extends Controller
{
    public function backup()
    {
        Artisan::call('backup:run',['--only-db'=>true]);
        $output = Artisan::output();
        dump($output);
    }
}
