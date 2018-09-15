<?php declare(strict_types=1);

namespace Stilus\Platform\Controller;

use Igni\Application\Http\Controller;
use Igni\Network\Http\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class CreatePlatform implements Controller
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {

    }

    public static function getRoute(): Route
    {
        return Route::post('/platform');
    }
}
