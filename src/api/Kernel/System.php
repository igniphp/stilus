<?php declare(strict_types=1);

namespace Stilus\Kernel;

use Igni\Application\Config;
use Igni\Container\ServiceLocator;
use Igni\Storage\Driver\Connection;
use Igni\Storage\Driver\ConnectionManager;
use Stilus\Exception\BootException;
use Stilus\Platform\PlatformModule;
use Symfony\Component\Yaml\Yaml;
use Throwable;
use Igni\Storage\Driver\Pdo\Connection as PdoConnection;

final class System
{
    public const STILUS_MODULES = [
        PlatformModule::class,
    ];

    public const STILUS_DIR = __DIR__ . '/../../..';
    public const STILUS_DATA_DIR = self::STILUS_DIR . '/data';
    public const STILUS_DB_PATH = self::STILUS_DATA_DIR . '/stilus.db';
    public const STILUS_BASE_CONFIG = self::STILUS_DIR . '/.stilus.yml';
    public const STILUS_VENDOR_DIR = self::STILUS_DIR . '/vendor';
    public const STILUS_VENDOR_AUTOLOADER = self::STILUS_VENDOR_DIR . '/autoload.php';

    /** @var Config */
    private $config;

    private $serviceLocator;

    public function __construct()
    {
        if (version_compare('7.1.0', PHP_VERSION, '>')) {
            throw BootException::forInvalidPHPVersion(PHP_VERSION);
        }

        if (!file_exists(self::STILUS_VENDOR_AUTOLOADER)) {
            throw BootException::forMissingComposer();
        }

        require_once self::STILUS_VENDOR_AUTOLOADER;
    }

    public function createConnection(): Connection
    {
        if (!ConnectionManager::has('default')) {
            ConnectionManager::register('default', new PdoConnection('sqlite:' . self::STILUS_DB_PATH));
        }


        return ConnectionManager::get('default');
    }

    public function getBaseConfig(): Config
    {
        if ($this->config instanceof Config) {
            return $this->config;
        }

        $config = $this->loadBaseConfig();
        return $this->config = new Config([
            'dir.basedir', System::STILUS_DIR,
            'dir.config' => realpath(System::STILUS_DIR . DIRECTORY_SEPARATOR . $config['paths']['config']),
            'dir.database', realpath(System::STILUS_DIR . DIRECTORY_SEPARATOR . $config['paths']['database']),
            'dir.themes', realpath(System::STILUS_DIR . DIRECTORY_SEPARATOR . $config['paths']['themes']),
        ]);
    }

    public function loadBaseConfig(): array
    {
        if (!is_readable(self::STILUS_BASE_CONFIG)) {
            throw BootException::forMissingBaseConfiguration();
        }

        try {
            return $configuration = Yaml::parseFile(self::STILUS_BASE_CONFIG);
        } catch (Throwable $throwable) {
            throw BootException::forInvalidBaseConfiguration($throwable);
        }
    }

    public function createServiceLocator(): ServiceLocator
    {
        $container = new ServiceLocator();
        $container->set(Config::class, $this->getBaseConfig());

        return $container;
    }
}
