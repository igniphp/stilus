<?php declare(strict_types=1);

namespace Stilus;

use Igni\Container\ServiceLocator;
use Igni\Http\Application;
use Igni\Http\Server;

use Stilus\Hello\HelloModule;

/**
 * Keeps modules configuration and database
 */
const STILUS_DIR = __DIR__  . '/..';

/**
 * Vendor directory path
 */
const STILUS_VENDOR_DIR = __DIR__ . '/../vendor';

/**
 * Composer autoloader file
 */
const STILUS_VENDOR_AUTOLOADER = __DIR__ . '/../vendor/autoload.php';

// Bootstrap
(new class {

    private static function setupAutoload(): void
    {
        if (!file_exists(STILUS_VENDOR_AUTOLOADER)) {
            throw new \RuntimeException('Vendor dir is missing. Did you forgot to run composer install?');
        }

        require __DIR__ . '/../vendor/autoload.php';
    }

    private static function getApplicationConfig(): array
    {
        static $configuration;

        if ($configuration !== null) {
            return $configuration;
        }

        $applicationIni = STILUS_DIR . '/.application.ini';
        if (!is_readable($applicationIni)) {
            throw new \RuntimeException('.application.ini file is missing. Did you remove it by accident?');
        }

        return $configuration = parse_ini_file($applicationIni);
    }

    private static function setupServer(): Server
    {
        $config = self::getApplicationConfig();

        if (!isset($config['http_port'])) {
            throw new \RuntimeException('http_port setting is missing in application.ini file.');
        }

        if (!isset($config['http_address'])) {
            throw new \RuntimeException('http_address setting is missing in application.ini file.');
        }

        $httpConfiguration = new Server\HttpConfiguration($config['http_address'], (int) $config['http_port']);

        if (isset($config['http_max_connections'])) {
            $httpConfiguration->setMaxConnections((int) $config['http_max_connections']);
        }

        if (isset($config['http_workers'])) {
            $httpConfiguration->setWorkers((int) $config['http_workers']);
        }

        if (isset($config['http_max_requests'])) {
            $httpConfiguration->setMaxRequests((int) $config['http_max_requests']);
        }

        if (isset($config['http_pid'])) {
            $httpConfiguration->enableDaemon(STILUS_DIR . DIRECTORY_SEPARATOR . $config['http_pid']);
        }

        if (isset($config['log_file'])) {
            $httpConfiguration->setLogFile(STILUS_DIR . DIRECTORY_SEPARATOR . $config['log_file']);
        }

        define('STILUS_SERTVER_START', time());

        return new Server($httpConfiguration);
    }

    public static function main(): void
    {
        self::setupAutoload();

        $config = self::getApplicationConfig();
        $application = new Application();
        $application->extend(HelloModule::class);

        $server = null;
        if ($config['enable_server']) {
            $server = self::setupServer();
        }

        $application->run($server);
    }

// Run application in contained scope
})::main();
