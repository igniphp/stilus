<?php declare(strict_types=1);

namespace Stilus\Kernel;

use Igni\Application\Config;
use Igni\Container\ServiceLocator;
use Igni\Storage\Driver\ConnectionManager;
use Igni\Storage\Driver\Pdo\Connection;
use Psr\Container\ContainerInterface;
use Stilus\Exception\BootException;
use Stilus\Platform\PlatformModule;
use Symfony\Component\Yaml\Yaml;
use Throwable;

final class System
{
    public const DIR = __DIR__ . '/../../..';
    public const DATA_DIR = self::DIR . '/data';
    public const DB_PATH = self::DATA_DIR . '/stilus.db';
    public const VENDOR_DIR = self::DIR . '/vendor';
    public const VENDOR_AUTOLOADER = self::VENDOR_DIR . '/autoload.php';
    public const BASE_CONFIG = self::DIR . '/.stilus.yml';
    public const BASE_MODULES = [
        PlatformModule::class,
    ];

    /** @var Config */
    private $config;

    private $container;

    public function __construct()
    {
        if (version_compare('7.1.0', PHP_VERSION, '>')) {
            throw BootException::forInvalidPHPVersion(PHP_VERSION);
        }

        if (!file_exists(self::VENDOR_AUTOLOADER)) {
            throw BootException::forMissingComposer();
        }

        require_once self::VENDOR_AUTOLOADER;
    }

    public function createDatabaseConnection(): Connection
    {
        if (!ConnectionManager::has('default')) {
            ConnectionManager::register('default', new Connection('sqlite:' . self::DB_PATH));
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
            'dir.basedir' => System::DIR,
            'dir.config' => realpath(System::DIR . DIRECTORY_SEPARATOR . $config['paths']['config']),
            'dir.database' => realpath(System::DIR . DIRECTORY_SEPARATOR . $config['paths']['database']),
            'dir.themes' => realpath(System::DIR . DIRECTORY_SEPARATOR . $config['paths']['themes']),
        ]);
    }

    private function loadBaseConfig(): array
    {
        if (!is_readable(self::BASE_CONFIG)) {
            throw BootException::forMissingBaseConfiguration();
        }

        try {
            return $configuration = Yaml::parseFile(self::BASE_CONFIG);
        } catch (Throwable $throwable) {
            throw BootException::forInvalidBaseConfiguration($throwable);
        }
    }

    public function createServiceLocator(): ServiceLocator
    {
        if (!$this->container instanceof ContainerInterface) {
            $this->container = new ServiceLocator();
            $this->container->set(Config::class, $this->getBaseConfig());
        }

        return $this->container;
    }
}
