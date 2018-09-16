<?php declare(strict_types=1);

namespace Stilus\Platform;

use Igni\Storage\Driver\Pdo\Repository;

class UserRepository extends Repository
{
    public function findUserByEmail(string $email): User
    {
        $cursor = $this->query(
            'SELECT *FROM users WHERE email = :email LIMIT 1',
            [
                'email' => $email,
            ]
        );
        $cursor->hydrateWith($this->hydrator);
        $user = $cursor->current();
        $cursor->close();

        if ($user === null) {

        }

        return $user;
    }

    public static function getEntityClass(): string
    {
        return User::class;
    }
}
