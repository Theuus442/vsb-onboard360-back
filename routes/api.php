<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ParceiroController;
use App\Http\Controllers\ChecklistParceiroController;
use App\Http\Controllers\DocumentoController;

Route::get('/status', fn() => response()->json(['status' => 'API está online!']));

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/registrar', [AuthController::class, 'registrar']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    });
});

/*
|--------------------------------------------------------------------------
| Rotas Protegidas por Autenticação (auth:sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Rotas de Usuários - Somente Administrador Geral
    |--------------------------------------------------------------------------
    */
    Route::middleware('administrador')->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index']);
        Route::post('/usuarios', [UsuarioController::class, 'store']);
        Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);
        Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);
        Route::get('/usuarios/departamentos', [UsuarioController::class, 'listarDepartamentos']);
    });

    /*
    |--------------------------------------------------------------------------
    | Rotas de Checklists - Equipe Interna
    |--------------------------------------------------------------------------
    */
    Route::middleware('interno')->group(function () {
        Route::get('/checklists', [ChecklistParceiroController::class, 'index']);
        Route::post('/checklists', [ChecklistParceiroController::class, 'store']);
        Route::put('/checklists/{id}', [ChecklistParceiroController::class, 'update']);
    });

    /*
    |--------------------------------------------------------------------------
    | Rotas para Parceiros Autenticados (parceiro ou admin_parceiro)
    |--------------------------------------------------------------------------
    */
    Route::middleware('parceiro')->group(function () {
        Route::get('/meu-perfil', [ParceiroController::class, 'perfil']);

        Route::get('/documentos', [DocumentoController::class, 'index']);
        Route::post('/documentos', [DocumentoController::class, 'store']);
        Route::get('/documentos/{id}/download', [DocumentoController::class, 'download']);
    });

    /*
    |--------------------------------------------------------------------------
    | Gerenciamento de Documentos e Parceiros - Apenas Administrador Geral
    |--------------------------------------------------------------------------
    */
    Route::middleware('administrador')->group(function () {
        Route::put('/documentos/{id}/status', [DocumentoController::class, 'alterarStatus']);
        Route::delete('/documentos/{id}', [DocumentoController::class, 'destroy']);

        Route::get('/parceiros', [ParceiroController::class, 'index']);
        Route::post('/parceiros', [ParceiroController::class, 'store']);
        Route::get('/parceiros/{id}', [ParceiroController::class, 'show']);
        Route::put('/parceiros/{id}', [ParceiroController::class, 'update']);
        Route::delete('/parceiros/{id}', [ParceiroController::class, 'destroy']);
        Route::put('/parceiros/{id}/toggle-status', [ParceiroController::class, 'toggleStatus']);
    });

    /*
    |--------------------------------------------------------------------------
    | Gerenciamento de Usuários do Parceiro - Admin Parceiro
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin_parceiro')->group(function () {
        Route::get('/parceiro/usuarios', [ParceiroController::class, 'usuarios']);
        Route::post('/parceiro/usuarios', [ParceiroController::class, 'adicionarUsuario']);
        Route::delete('/parceiro/usuarios/{usuarioId}', [ParceiroController::class, 'removerUsuario']);
    });
});
