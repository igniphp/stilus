<?php declare(strict_types=1);

namespace Stilus;

use Igni\Http\Application;
use Igni\Http\Server;

use Stilus\Hello\HelloModule;

// Bootstrap
(new class {
    public static function main(): void
    {
        require __DIR__ . '/../vendor/autoload.php';

        $application = new Application();

        $configuration = new Server\HttpConfiguration(
            getenv('APPLICATION_ADDRESS'),
            (int) getenv('APPLICATION_PORT')
        );

        // Setup http server, attach additional listeners, etc:
        $server = new Server($configuration);

        $application->extend(HelloModule::class);

        // Application start here
        $application->run($server);
    }
// Execute main method in the bootstrap class
})::main();
