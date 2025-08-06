<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        'api' => [
            \Illuminate\Http\Middleware\HandleCors::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];



    protected $routeMiddleware = [
        'administrador' => \App\Http\Middleware\GarantirUsuarioAdministrador::class,
        'interno'       => \App\Http\Middleware\GarantirUsuarioInterno::class,
        'parceiro'      => \App\Http\Middleware\GarantirUsuarioParceiro::class,
        'admin_parceiro' => \App\Http\Middleware\GarantirUsuarioAdminParceiro::class,


    ];
}
