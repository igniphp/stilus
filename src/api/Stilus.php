<?php declare(strict_types=1);

use Igni\Application\HttpApplication;
use Igni\Network\Server\Configuration;
use Igni\Network\Server\HttpServer;
use Stilus\Exception\BootException;
use Stilus\Kernel\System;
use Igni\Storage\Driver\Connection;
use OpenApi\Annotations as Doc;

// Composer is autoloading this file even when test are run, this hack stops from
// excecution while tests are runnnig
if (defined('STILUS_TEST')) {
    return;
}

const STILUS_VERSION = "1.0.0";

/**
 * @Doc\Info(
 *     version=STILUS_VERSION,
 *     title="Stilus API",
 *     @Doc\License(
 *         name="BSD-3-Clause",
 *         url="https://www.opensource.org/licenses/BSD-3-Clause"
 *     )
 * )
 */
$system = new System();

// Bootstrap
(new class($system) {

    private $system;

    public function __construct(System $system)
    {
        $this->system = $system;
    }

    private function setupServer(array $config): HttpServer
    {
        if (!isset($config['port'])) {
            throw BootException::forMissingConfigurationOption('api.http_server.port');
        }

        if (!isset($config['address'])) {
            throw BootException::forMissingConfigurationOption('api.http_server.address');
        }

        $httpConfiguration = new Configuration((int) $config['port'], $config['address']);

        if (isset($config['max_connections'])) {
            $httpConfiguration->setMaxConnections((int) $config['max_connections']);
        }

        if (isset($config['workers'])) {
            $httpConfiguration->setWorkers((int) $config['workers']);
        }

        if (isset($config['max_requests'])) {
            $httpConfiguration->setMaxRequests((int) $config['max_requests']);
        }

        define('STILUS_SERVER_ENABLED', true);
        define('STILUS_SERVER_START', time());

        return new HttpServer($httpConfiguration);
    }

    public function main(): void
    {
        $config = $this->system->getBaseConfig();
        $connection = $this->system->createDatabaseConnection();

        $serviceLocator = $this->system->createServiceLocator();
        $serviceLocator->set(Connection::class, $connection);
        $application = new HttpApplication($serviceLocator);

        foreach (System::STILUS_MODULES as $module) {
            $application->extend($module);
        }

        $server = null;

        // Server should be only available in sapi mode and when proper config is set
        if (php_sapi_name() == "cli" &&
            isset($config['api']) &&
            isset($config['api']['http_server']) &&
            isset($config['api']['http_server']['enable']) &&
            $config['api']['http_server']['enable']
        ) {
            $server = $this->setupServer($config['api']['http_server']);
        }

        $application->run($server);
    }

// Run application in contained scope
})->main();
