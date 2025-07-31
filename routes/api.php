<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ParceiroController;
use App\Http\Controllers\ChecklistParceiroController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::post('/registrar', [AuthController::class, 'registrar']);


Route::get('/status', function () {
    return response()->json(['status' => 'API está online!']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::middleware('administrador')->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index']);
        Route::post('/usuarios', [UsuarioController::class, 'store']);
        Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);
        Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);
    });

    Route::middleware('interno')->group(function () {
        Route::get('/checklists', [ChecklistParceiroController::class, 'index']);
        Route::post('/checklists', [ChecklistParceiroController::class, 'store']);
        Route::put('/checklists/{id}', [ChecklistParceiroController::class, 'update']);
    });

    Route::middleware('parceiro')->group(function () {
        Route::get('/meu-perfil', [ParceiroController::class, 'perfil']);
        Route::get('/documentos', [DocumentoController::class, 'index']);
        Route::post('/documentos', [DocumentoController::class, 'store']);
        Route::get('/documentos/{id}/download', [DocumentoController::class, 'download']);
    });

    Route::middleware(['administrador', 'interno'])->group(function () {
        Route::put('/documentos/{id}/status', [DocumentoController::class, 'alterarStatus']);
        Route::delete('/documentos/{id}', [DocumentoController::class, 'destroy']);
    });
});
