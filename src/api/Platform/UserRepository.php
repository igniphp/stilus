<?php declare(strict_types=1);

namespace Stilus\Platform;

use Igni\Storage\Driver\Pdo\Repository;
use Stilus\Platform\Exception\UserException;

class UserRepository extends Repository
{
    public function createSchema(): void
    {
        $cursor = $this->connection->execute('CREATE TABLE IF NOT EXISTS "users" (
            "id" char(22) PRIMARY KEY NOT NULL,
            "email" char(128) NOT NULL,
            "password" char(128) NOT NULL
          );'
        );
        $cursor->execute();
    }

    public function dropSchema(): void
    {
        $cursor = $this->connection->execute('DROP TABLE IF EXISTS "users"');
        $cursor->execute();
    }

    public function findUserByEmail(string $email): User
    {
        $cursor = $this->query(
            'SELECT * FROM users WHERE email = :email LIMIT 1',
            [
                'email' => $email,
            ]
        );
        $cursor->hydrateWith($this->hydrator);
        $user = $cursor->current();
        $cursor->close();

        if ($user === null) {
            throw UserException::forNotFound();
        }

        return $user;
    }

    public static function getEntityClass(): string
    {
        return User::class;
    }
}
