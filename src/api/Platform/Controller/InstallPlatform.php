<?php declare(strict_types=1);

namespace Stilus\Platform\Controller;

use Igni\Application\Http\Controller;
use Igni\Network\Http\Route;
use OpenApi\Annotations as Doc;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stilus\Platform\PlatformService;

/**
 * @Doc\Post(
 *     path="/platform",
 *     summary="Performs platform installation",
 *     tags={"platform"},
 *     operationId="platformInstall",
 *     @Doc\Response(
 *         response=201,
 *         description="Platform successfully installed",
 *         @Doc\Schema(ref="#/components/schemas/Platform")
 *     ),
 *     @Doc\Response(
 *         response="default",
 *         description="Unexpected error",
 *         @Doc\Schema(ref="#/components/schemas/Error")
 *     )
 * )
 */
final class InstallPlatform implements Controller
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
