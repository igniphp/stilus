<?php declare(strict_types=1);

namespace Stilus\Platform\Persistence;

use Igni\Storage\Id;
use Igni\Storage\Id\Uuid;
use Igni\Storage\Mapping\Annotation as Storage;
use Igni\Storage\Storable;
use Igni\Validation\Constraint;
use Stilus\Platform\Exception\UserException;

use function password_hash;
use function password_verify;

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

    public function __construct(string $email, string $password)
    {
        $this->id = new Uuid();
        $this->email = $email;
        $this->createPassword($password);
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
        return password_verify($password, $this->password);
    }

    public function createPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function changePassword(string $oldPassword, string $newPassword): bool
    {
        if (!$this->validatePassword($oldPassword)) {
            return false;
        }

        $this->createPassword($newPassword);

        return true;
    }

    private function validate()
    {
        if (!Constraint::email()->validate($this->email)) {
            throw UserException::forCreationFailure();
        }
    }
}
