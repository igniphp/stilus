<?php declare(strict_types=1);

namespace Stilus\Tests\Functional\Platform;

use Faker\Factory as FakerFactory;
use Faker\Generator;
use Igni\Storage\EntityManager;
use PHPUnit\Framework\TestCase;
use Stilus\Exception\EntityNotFound;
use Stilus\Platform\User;
use Stilus\Platform\UserRepository;
use Stilus\Tests\StorageTestTrait;

final class UserRepositoryTest extends TestCase
{
    use StorageTestTrait;

    /** @var Generator */
    private $faker;

    public function setUp()
    {
        parent::setUp();
        $this->createConnection();
        $this->faker = FakerFactory::create();
    }

    public function testFindByEmail(): void
    {
        $repository = $this->getUserRepository();
        $this->createTestUsers($repository);

        $email = 'test@user.com';
        $repository->create(new User($email, 'test'));

        $user = $repository->findUserByEmail($email);
        self::assertInstanceOf(User::class, $user);
        self::assertSame($email, $user->getEmail());
        self::assertTrue($user->validatePassword('test'));
    }

    public function testFailFindByEmail(): void
    {
        $this->expectException(EntityNotFound::class);
        $repository = $this->getUserRepository();
        $this->createTestUsers($repository);

        $repository->findUserByEmail('test');
    }

    private function getUserRepository(): UserRepository
    {
        $entityManager = new EntityManager();
        $repository = new UserRepository($entityManager, $this->connection);
        $repository->dropSchema();
        $repository->createSchema();

        return $repository;
    }

    private function createTestUsers(UserRepository $repository, int $amount = 5)
    {
        for ($i = 0; $i < $amount; $i++) {
            $user = new User($this->faker->email, $this->faker->password);
            $repository->create($user);
        }
    }
}
