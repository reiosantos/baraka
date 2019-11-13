<?php
namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;


/**
 * Class Feedback
 * @ORM\Entity
 * @ORM\Table(name="feedback")
 */
class Feedback
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
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=64, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="location", type="string", length=255, nullable=true)
     */
    private $location;

    /**
     * @var string|null
     *
     * @ORM\Column(name="message", type="text", length=255, nullable=true)
     */
    private $message;

    /**
     * @var string|null
     *
     * @ORM\Column(name="upload_date", type="datetime")
     */
    private $date;

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
     * @return Feedback
     */
    public function setUsername(?string $username): Feedback
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return Feedback
     */
    public function setEmail(?string $email): Feedback
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string|null $location
     * @return Feedback
     */
    public function setLocation(?string $location): Feedback
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     * @return Feedback
     */
    public function setMessage(?string $message): Feedback
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDate(): ?string
    {
        return $this->date;
    }

    /**
     * @param string|null $date
     * @return Feedback
     * @throws Exception
     */
    public function setDate(?string $date = null): Feedback
    {
        if ($date !== null) {
            $this->date = $date;
        } else {
            $this->date = new DateTime('now');
        }
        return $this;
    }
}
