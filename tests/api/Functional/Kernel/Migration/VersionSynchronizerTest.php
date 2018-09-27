<?php declare(strict_types=1);

namespace Stilus\Tests\Functional\Platform;

use Igni\Storage\Migration\Version;
use PHPUnit\Framework\TestCase;
use Stilus\Kernel\Migration\VersionSynchronizer;
use Stilus\Tests\StorageTestTrait;

final class VersionSynchronizerTest extends TestCase
{
    use StorageTestTrait;

    public function setUp()
    {
        $this->createConnection();
        $this->connection->createCursor('DROP TABLE IF EXISTS migrations')->execute();
        parent::setUp();
    }

    public function testCanInstantiate(): void
    {
        $synchronizer = new VersionSynchronizer($this->connection);
        self::assertInstanceOf(VersionSynchronizer::class, $synchronizer);
    }

    public function testSetAndGetVersion(): void
    {
        $synchronizer = new VersionSynchronizer($this->connection);
        $synchronizer->setVersion(Version::fromString('1.0.0'));
        $synchronizer->setVersion(Version::fromString('1.2.0'));

        $synchronizer = new VersionSynchronizer($this->connection);

        self::assertTrue($synchronizer->getVersion()->equalsLiteral('1.2.0'));

        $cursor = $this->connection->createCursor('SELECT *FROM migrations');
        self::assertCount(2, $cursor->toArray());
    }
}
