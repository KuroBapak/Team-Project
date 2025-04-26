<?php


use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Request;
use App\Http\Middleware\AdminAuth; // Add this line to import the AdminAuth middleware


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminAuth::class,
            'delivery' => \App\Http\Middleware\DeliveryAuth::class,
        ]);
        // Redirect tamu yang tidak terautentikasi ke halaman login
        $middleware->redirectGuestsTo('/login');

        // Atau menggunakan closure jika ingin lebih fleksibel
        $middleware->redirectGuestsTo(fn (Request $request) => route('login'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();  // Tanda koma setelah ini sudah dihapus
