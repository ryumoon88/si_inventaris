<?php

use App\Http\Controllers\ItemTransactionExportController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::post('/item-transactions/export', [ItemTransactionExportController::class, 'export'])
//     ->name('filament.resources.item-transactions.export');

Route::post('/generate-report', function (Request $request) {
    dd($request);
});
