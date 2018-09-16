<?php declare(strict_types=1);

namespace Stilus\Platform;

use Igni\Storage\Id;
use Igni\Storage\Id\Uuid;
use Igni\Storage\Mapping\Annotation as Storage;
use Igni\Storage\Storable;
use Igni\Validation\Constraint;

use function password_hash;
use Stilus\Platform\Exception\UserException;

/**
 * @Storage\Entity(source="users")
 */
final class User implements Storable
{
    /**
     * @var Uuid
     * @Storage\Property\Id(Uuid::class)
     */
    private $id;

    /**
     * @var
     * @Storage\Property\Text()
     */
    private $email;

    /**
     * @var
     * @Storage\Property\Text()
     */
    private $password;

    /**
     * @var
     * @Storage\Property\Text()
     */
    private $salt;

    public function __construct(string $email, string $password)
    {
        $this->id = new Uuid();
        $this->email = $email;
        $this->password = $password;
        $this->validate();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function validatePassword(string $password): bool
    {
        return $this->password === $this->generateHash($password);
    }

    public function createPassword(string $password): void
    {
        $this->salt = bin2hex(random_bytes(10));
        $this->password = $this->generateHash($password);
    }

    private function generateHash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['salt' => $this->salt]);
    }

    private function validate()
    {
        if (!Constraint::email()->validate($this->email)) {
            throw UserException::forUserCreation();
        }
    }
}
