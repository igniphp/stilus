<?php declare(strict_types=1);

namespace Stilus\Platform\Controller;

use Igni\Application\Http\Controller;
use Igni\Network\Http\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stilus\Platform\PlatformService;

final class GetPlatformStatus implements Controller
{
    private $platformService;

    public function __construct(PlatformService $platformService)
    {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {

    }

    public static function getRoute(): Route
    {
        return Route::get('/platform');
    }
}
