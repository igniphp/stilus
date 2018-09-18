<?php declare(strict_types=1);

namespace Stilus\Platform\Controller;

use Igni\Application\Http\Controller;
use Igni\Network\Http\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stilus\Platform\PlatformService;

final class CreatePlatform implements Controller
{
    private $platformService;

    public function __construct(PlatformService $platformService)
    {
        $this->platformService = $platformService;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->platformService->install();
    }

    public static function getRoute(): Route
    {
        return Route::post('/platform');
    }
}
