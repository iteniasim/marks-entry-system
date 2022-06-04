<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    public function backupDatabase()
    {
        Artisan::call('backup:run --only-db');
        return 'back up successfull, check storage/backup directory to find the backup file';
    }
}
