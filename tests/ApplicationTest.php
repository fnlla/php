<?php

declare(strict_types=1);

/*
===============================================================================
FNLLA PHP TEST CASE
File: tests\ApplicationTest.php
Copyright (c) 2026 TechAyo LTD (techayo.co.uk). Released under the MIT License.
===============================================================================

FNLLA PHP is produced, maintained and distributed by TechAyo LTD
(techayo.co.uk). This repository is the authoritative maintainer workspace for
the FNLLA PHP framework released under the MIT License and its related delivery scripts, tests,
templates and release metadata.

Purpose:
- Validates maintained framework behavior inside the repository-local test harness.
*/

namespace Fnlla\Php\Tests;

use Fnlla\Php\Application;
use Fnlla\Php\Container\Container;
use Fnlla\Php\Exceptions\ExceptionHandler;
use Fnlla\Php\Http\Request;
use Fnlla\Php\Http\Response;
use Fnlla\Php\Middleware\MiddlewareInterface;
use Fnlla\Php\Routing\Router;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class ApplicationTest extends TestCase
{
    private string $logPath;

    protected function setUp(): void
    {
        $this->logPath = storage_path("logs/test.log");
        if (is_file($this->logPath)) {
            unlink($this->logPath);
        }
    }

    public function testHeadRequestsKeepStatusAndHeadersButDropBody(): void
    {
        $container = new Container();
        $router = new Router($container);
        $router->get("/status", static fn (): Response => Response::html("ok"));

        $application = new Application($router, $container, new ExceptionHandler());
        $response = $application->handle(Request::capture("", [
            "REQUEST_URI" => "/status",
            "REQUEST_METHOD" => "HEAD",
        ]));

        self::assertSame(200, $response->status());
        self::assertSame("", $response->body());
        self::assertArrayHasKey("X-Request-Id", $response->headers());
        self::assertArrayHasKey("X-Content-Type-Options", $response->headers());
    }

    public function testApiExceptionsReturnJsonAndAreLogged(): void
    {
        $container = new Container();
        $router = new Router($container);
        $router->get("/api/fail", static function (): never {
            throw new RuntimeException("Boom");
        });

        $application = new Application($router, $container, new ExceptionHandler());
        $response = $application->handle(Request::capture("", [
            "REQUEST_URI" => "/api/fail",
            "REQUEST_METHOD" => "GET",
            "HTTP_ACCEPT" => "application/json",
        ]));

        self::assertSame(500, $response->status());
        self::assertStringContainsString('"error": "Server Error"', $response->body());
        self::assertStringContainsString('"request_id":', $response->body());
        self::assertFileExists($this->logPath);
        self::assertStringContainsString("Boom", (string) file_get_contents($this->logPath));
    }

    public function testGlobalMiddlewareAliasWrapsApplicationRequests(): void
    {
        $container = new Container();
        $container->singleton("test.middleware", static fn (): MiddlewareInterface => new class implements MiddlewareInterface {
            public function handle(Request $request, callable $next): mixed
            {
                $response = $next($request);

                return $response instanceof Response
                    ? $response->withHeader("X-Global-Middleware", "applied")
                    : $response;
            }
        });

        $router = new Router($container);
        $router->middleware("test", "test.middleware");
        $router->get("/wrapped", static fn (): Response => Response::html("ok"));

        $application = new Application($router, $container, new ExceptionHandler());
        $application->middleware("test");

        $response = $application->handle(Request::capture("", [
            "REQUEST_URI" => "/wrapped",
            "REQUEST_METHOD" => "GET",
        ]));

        self::assertSame(200, $response->status());
        self::assertSame("applied", $response->headers()["X-Global-Middleware"] ?? null);
    }
}
