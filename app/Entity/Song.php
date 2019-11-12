<?php
namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;


/**
 * Class Song
 * @ORM\Entity
 * @ORM\Table(name="songs")
 * @ORM\HasLifecycleCallbacks
 */
class Song extends Uploader
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
     * @ORM\Column(name="upload_date", type="datetime")
     */
    private $uploadDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="file_name", type="string", length=255, nullable=true)
     */
    private $fileName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="album_art_name", type="string", length=255, nullable=true)
     */
    private $albumArtName;

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
    public function getUploadDate(): ?string
    {
        return $this->uploadDate;
    }

    /**
     * @param string|null $uploadDate
     * @throws Exception
     */
    public function setUploadDate(?string $uploadDate): void
    {
        $this->uploadDate = new DateTime('now');
    }

    /**
     * @return string|null
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param string|null $fileName
     */
    public function setFileName(?string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string|null
     */
    public function getAlbumArtName(): ?string
    {
        return $this->albumArtName;
    }

    /**
     * @param string|null $albumArtName
     */
    public function setAlbumArtName(?string $albumArtName): void
    {
        $this->albumArtName = $albumArtName;
    }

    /**
     * {@inheritDoc}
     */
    public function setPath(?string $uploadFieldName, mixed $file, bool $override = false): mixed
    {
        parent::setPath($uploadFieldName, $file, $override);

        if ($uploadFieldName === 'albumArtName') {
            $this->setAlbumArtName($file['path']);
        } elseif ($uploadFieldName === 'fileName') {
            $this->setFileName($file['path']);
        }
        return $file;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload(): void
    {

        if ($this->albumArtName) {
            unlink($this->albumArtName);
        }
        if ($this->fileName) {
            unlink($this->fileName);
        }
    }
}
