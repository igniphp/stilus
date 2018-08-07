<?php declare(strict_types=1);

namespace Stilus\Hello;

use Igni\Http\Controller;
use Igni\Http\Response;
use Igni\Http\Router\Route;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SayHello implements Controller
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return Response::fromText("Hello {$request->getAttribute('name')}!");
    }

    /*
     * Initial commit
     */
    public static function getRoute(): Route
    {
        return Route::get('/hello/{name}');
    }

    //pull test2
}
