<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\InvoiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function(){

    Route::get('/users', [UserController::class, 'index']);
    Route::apiResource('invoices', InvoiceController::class);
    // Route::get('/invoices', [InvoiceController::class, 'index']);
    // Route::get('/invoices/{invoice}', [InvoiceController::class, 'show']);
    // Route::post('/invoices', [InvoiceController::class, 'store']);
    // Route::put('/invoices/{invoice}', [InvoiceController::class, 'update']);
    // Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy']);
    
    //Autenticacao
    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/users/{user}', [UserController::class, 'show'])->middleware('ability:user-get');
        // Route::get('/test', [TestController::class, 'index'])->middleware('auth:sanctum'); //Exemplo 
        Route::get('/test', [TestController::class, 'index'])->middleware('auth:sanctum')->middleware('ability:user-get'); 
        Route::post('/logout', [AuthController::class, 'logout']); 
        
    }); 
    
    Route::post('/login', [AuthController::class, 'login']); 

});
    
