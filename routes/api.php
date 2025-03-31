<?php

use App\Http\Controllers\EmployeeImportController;
use Illuminate\Support\Facades\Route;

Route::post('/import', [EmployeeImportController::class, 'import']);
