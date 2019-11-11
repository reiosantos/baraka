<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Class Artist
 * @ORM\Entity
 * @ORM\Table(name="artists")
 * @ORM\HasLifecycleCallbacks
 */
class Artist extends Uploader
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="details", type="text", nullable=true)
     */
    private $details;

    /**
     * @var string|null
     *
     * @ORM\Column(name="photo_name", type="string", length=255, nullable=true)
     */
    private $photoName;

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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getDetails(): ?string
    {
        return $this->details;
    }

    /**
     * @param string|null $details
     */
    public function setDetails(?string $details): void
    {
        $this->details = $details;
    }

    /**
     * @return string|null
     */
    public function getPhotoName(): ?string
    {
        return $this->photoName;
    }

    /**
     * @param string|null $photoName
     */
    public function setPhotoName(?string $photoName): void
    {
        $this->photoName = $photoName;
    }

    /**
     * {@inheritDoc}
     */
    public function setPath(?string $uploadFieldName, mixed $file, bool $override = false): mixed
    {
        parent::setPath($uploadFieldName, $file, $override);
        $this->setPhotoName($file['path']);
        return $file;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload(): void
    {
        $file = $this->photoName;
        if ($file) {
            unlink($file);
        }
    }
}
