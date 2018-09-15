<?php declare(strict_types=1);

namespace Stilus;

use Igni\Application\Config;
use Igni\Application\HttpApplication;
use Igni\Container\ServiceLocator;
use Igni\Network\Server\HttpServer;
use Igni\Network\Server\Configuration;
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

    private function loadBootstrapConfig(): array
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
        $this->setupAutoload();

        $config = $this->loadBootstrapConfig();
        $container = new ServiceLocator();
        $container->share(Config::class, function() use ($config) {
            return new Config([
                'dir.basedir', STILUS_DIR,
                'dir.config' => realpath(STILUS_DIR . DIRECTORY_SEPARATOR . $config['paths']['config']),
                'dir.database', realpath(STILUS_DIR . DIRECTORY_SEPARATOR . $config['paths']['database']),
                'dir.themes', realpath(STILUS_DIR . DIRECTORY_SEPARATOR . $config['paths']['themes']),
            ]);
        });
        $application = new HttpApplication($container);

        foreach (STILUS_MODULES as $module) {
            $application->extend($module);
        }

        $server = null;
        if (isset($config['api']) &&
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
