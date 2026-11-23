<?php

use App\Http\Middleware\EnsureUserIsModerator;
use App\Http\Responses\AuthResponse;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\ValidationResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        api: __DIR__ . '/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();
        $middleware->alias([
            'moderator' => EnsureUserIsModerator::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (ValidationException $e, Request $request) {
            return (new ValidationResponse($e->errors()))->toResponse($request);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return (new AuthResponse())->toResponse($request);
        });

        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            $message = match ($e->getStatusCode()) {
                403 => 'Доступ запрещен.',
                404 => 'Запрашиваемая страница не существует.',
                default => null,
            };

            return (new ErrorResponse($e, message: $message))->toResponse($request);
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            return (new ErrorResponse(
                $e,
                message: 'Внутренняя ошибка сервера.',
                code: 500
            ))->toResponse($request);
        });
    })->create();
