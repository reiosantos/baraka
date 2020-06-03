<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @var ArrayCollection[]|null
     * @ORM\OneToMany(targetEntity="App\Entity\Song", mappedBy="artist")
     */
    private $songs;

    public function __construct()
    {
        $this->songs = new ArrayCollection();
    }

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
     * @return Artist
     */
    public function setName(?string $name): Artist
    {
        $this->name = $name;
        return $this;
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
     * @return Artist
     */
    public function setDetails(?string $details): Artist
    {
        $this->details = $details;
        return $this;
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
     * @return Artist
     */
    public function setPhotoName(?string $photoName): Artist
    {
        $this->photoName = $photoName;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setPath(?string $uploadFieldName, array $file, bool $override = false): array
    {
        $file = parent::setPath($uploadFieldName, $file, $override);
        $this->setPhotoName($file['path']);
        return $file;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload(): void
    {
        $file = $this->photoName;
        if ($file && file_exists($file)) {
            unlink($file);
        }
    }
}
