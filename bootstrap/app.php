<?php

use App\Http\Middleware\EnsureUserRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role' => EnsureUserRole::class,
        ]);

        $middleware->redirectGuestsTo(fn (): string => route('login_page'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();

if (! empty($_ENV['VERCEL']) || ! empty($_SERVER['VERCEL']) || getenv('VERCEL') !== false) {
    $vercelStoragePath = '/tmp/storage';
    foreach ([
        $vercelStoragePath,
        $vercelStoragePath . '/framework/cache/data',
        $vercelStoragePath . '/framework/sessions',
        $vercelStoragePath . '/framework/views',
        $vercelStoragePath . '/logs',
    ] as $dir) {
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }
    $app->useStoragePath($vercelStoragePath);
}

return $app;
