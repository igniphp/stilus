<?php declare(strict_types=1);
//test commit
namespace Stilus;

use Igni\Http\Application;
use Igni\Http\Server;
use Stilus\Exception\BootException;
use Stilus\Platform\PlatformModule;
use Symfony\Component\Yaml\Yaml;
use Throwable;


if (version_compare('7.1.0', PHP_VERSION, '>')) {
    throw BootException::forInvalidPHPVersion(PHP_VERSION);
}

const STILUS_MODULES = [
    PlatformModule::class
];

const STILUS_DIR = __DIR__  . '/../..';

const STILUS_BASE_CONFIG = STILUS_DIR . '/.stilus.yml';

const STILUS_VENDOR_DIR = __DIR__ . '/../../vendor';

const STILUS_VENDOR_AUTOLOADER = __DIR__ . '/../../vendor/autoload.php';

// Bootstrap
(new class {

    private function setupAutoload(): void
    {
        if (!file_exists(STILUS_VENDOR_AUTOLOADER)) {
            throw BootException::forMissingComposer();
        }

        require STILUS_VENDOR_AUTOLOADER;
    }

    private function loadApplicationConfig(): array
    {
        if (!is_readable(STILUS_BASE_CONFIG)) {
            throw BootException::forMissingBaseConfiguration();
        }

        try {
            return $configuration = Yaml::parseFile(STILUS_BASE_CONFIG);
        } catch (Throwable $throwable) {
            throw BootException::forInvalidBaseConfiguration($throwable);
        }
    }

    private function setupServer(array $config): Server
    {
        if (!isset($config['port'])) {
            throw BootException::forMissingConfigurationOption('api.http_server.port');
        }

        if (!isset($config['address'])) {
            throw BootException::forMissingConfigurationOption('api.http_server.address');
        }

        $httpConfiguration = new Server\HttpConfiguration($config['address'], (int) $config['port']);

        if (isset($config['max_connections'])) {
            $httpConfiguration->setMaxConnections((int) $config['max_connections']);
        }

        if (isset($config['workers'])) {
            $httpConfiguration->setWorkers((int) $config['workers']);
        }

        if (isset($config['max_requests'])) {
            $httpConfiguration->setMaxRequests((int) $config['max_requests']);
        }

        if (isset($config['pid_file'])) {
            $httpConfiguration->enableDaemon(STILUS_DIR . DIRECTORY_SEPARATOR . $config['pid_file']);
        }

        if (isset($config['log_file'])) {
            $httpConfiguration->setLogFile(STILUS_DIR . DIRECTORY_SEPARATOR . $config['log_file']);
        }

        \define('STILUS_SERVER_ENABLED', true);
        \define('STILUS_SERVER_START', time());

        return new Server($httpConfiguration);
    }

    public function main(): void
    {
        $this->setupAutoload();

        $config = $this->loadApplicationConfig();
        $application = new Application();

        foreach (STILUS_MODULES as $module) {
            $application->extend($module);
        }

        $server = null;
        if ($this->isServerEnable($config))
        {
            $server = $this->setupServer($config['api']['http_server']);
        }
        $application->run($server);
    }

    private function isServerEnable(array $config): bool
    {
        return isset($config['api']['http_server']['enable']) && $config['api']['http_server']['enable'];
    }

// Run application in contained scope
})->main();
