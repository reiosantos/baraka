<?php /** @noinspection MethodShouldBeFinalInspection */

namespace App\Entity;


use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Song
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @var integer|null
     *
     * @ORM\Column(name="ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="username", type="string", length=60, nullable=false, unique=true)
     */
    private $username;

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=120, nullable=false)
     */
    private $password;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = filter_var($username, FILTER_SANITIZE_STRING);
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @param string|null $password
     * @return bool
     */
    public function isPasswordValid(?string $password): bool
    {
        return password_verify($password, $this->getPassword());
    }

    /**
     * @return DateTime|null
     */
    public function getCreateDate(): ?DateTime
    {
        return $this->createDate;
    }

    /**
     * @param string|null $createDate
     * @return User
     */
    public function setCreateDate(?string $createDate = null): User
    {
        if ($createDate !== null) {
            $this->createDate = $createDate;
        } else {
            $this->createDate = new DateTime('now');
        }
        return $this;
    }
}
