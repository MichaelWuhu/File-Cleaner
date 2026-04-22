<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilenameCleanerController;

Route::get('/', [FilenameCleanerController::class, 'index']);
Route::post('/clean', [FilenameCleanerController::class, 'clean']);