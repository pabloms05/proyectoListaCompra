<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Manejar errores 403 (Forbidden) para listas compartidas
        $exceptions->render(function (HttpException $e, $request) {
            if ($e->getStatusCode() === 403) {
                // Si el error es de autorización y la ruta contiene 'listas'
                if (str_contains($request->path(), 'listas')) {
                    return redirect()->route('listas-compartidas')
                        ->with('error', $e->getMessage() ?: 'No tienes acceso a esta lista.');
                }
            }
        });
    })->create();
