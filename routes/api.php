<?php

use App\Http\Controllers\TarefaPadraoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::apiResource('usuarios', UsuarioController::class);
Route::apiResource('tarefas-padrao', TarefaPadraoController::class);
