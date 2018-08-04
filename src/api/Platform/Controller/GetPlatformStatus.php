<?php declare(strict_types=1);

namespace Stilus\Platform\Controller;

use Igni\Http\Controller;
use Igni\Http\Router\Route;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class GetPlatformStatus implements Controller
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {

    }

    public static function getRoute(): Route
    {
        return Route::get('/platform');
    }
}
