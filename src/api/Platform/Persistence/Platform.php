<?php declare(strict_types=1);

namespace Stilus\Platform\Persistence;

use OpenApi\Annotations as Doc;

/**
 * @Doc\Schema(
 *     description="Contains detailed information about platform status",
 *     title="Platform Information"
 * )
 */
final class Platform
{
    /**
     * @Doc\Property(
     *     title="installed",
     *     description="determines if platform is being installed",
     *     format="boolean"
     * )
     */
    private $installed = false;
    
    public function __construct()
    {

    }
}
