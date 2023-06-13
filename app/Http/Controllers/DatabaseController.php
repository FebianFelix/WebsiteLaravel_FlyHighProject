<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatabaseController extends Controller
{
    public function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            echo "Database connected successfully.";
        } catch (\Exception $e) {
            die("Could not connect to the database: " . $e->getMessage());
        }
    }
}
