<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ParceiroController;
use App\Http\Controllers\ChecklistParceiroController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/registrar', [AuthController::class, 'registrar']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    });
});

Route::get('/status', fn() => response()->json(['status' => 'API está online!']));

// Agrupamento autenticado
Route::middleware('auth:sanctum')->group(function () {

    // Usuários (apenas admin)
    Route::middleware('administrador')->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index']);
        Route::post('/usuarios', [UsuarioController::class, 'store']);
        Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);
        Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);
    });

    // Checklists (interno)
    Route::middleware('interno')->group(function () {
        Route::get('/checklists', [ChecklistParceiroController::class, 'index']);
        Route::post('/checklists', [ChecklistParceiroController::class, 'store']);
        Route::put('/checklists/{id}', [ChecklistParceiroController::class, 'update']);
    });

    // Documentos (parceiro)
    Route::middleware('parceiro')->group(function () {
        Route::get('/meu-perfil', [ParceiroController::class, 'perfil']);
        Route::get('/documentos', [DocumentoController::class, 'index']);
        Route::post('/documentos', [DocumentoController::class, 'store']);
        Route::get('/documentos/{id}/download', [DocumentoController::class, 'download']);
    });

    // Alteração de status e exclusão de documentos (apenas admin)
    Route::middleware('administrador')->group(function () {
        Route::put('/documentos/{id}/status', [DocumentoController::class, 'alterarStatus']);
        Route::delete('/documentos/{id}', [DocumentoController::class, 'destroy']);

        // Rotas de parceiros (apenas admin)
        Route::get('/parceiros', [ParceiroController::class, 'index']);
        Route::post('/parceiros', [ParceiroController::class, 'store']);
        Route::get('/parceiros/{id}', [ParceiroController::class, 'show']);
        Route::put('/parceiros/{id}', [ParceiroController::class, 'update']);
        Route::delete('/parceiros/{id}', [ParceiroController::class, 'destroy']);
    });

    // Associar usuários ao parceiro (requer apenas autenticação)
    Route::get('/parceiros/{id}/usuarios', [ParceiroController::class, 'usuarios']);
    Route::post('/parceiros/{id}/usuarios', [ParceiroController::class, 'adicionarUsuario']);
    Route::delete('/parceiros/{id}/usuarios/{usuarioId}', [ParceiroController::class, 'removerUsuario']);
});
