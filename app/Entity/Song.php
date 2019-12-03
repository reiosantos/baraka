<?php
namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use InvalidArgumentException;


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
     * @var DateTime|null
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
     * @var Artist|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Artist", inversedBy="songs", fetch="EAGER")
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="ID")
     */
    private $artist;

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
     * @return Song
     */
    public function setName(?string $name): Song
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUploadDate(): ?string
    {
        return $this->uploadDate->format('D, jS Y');
    }

    /**
     * @param string|null $uploadDate
     * @return Song
     * @throws Exception
     */
    public function setUploadDate(?string $uploadDate = null): Song
    {
        if ($uploadDate !== null) {
            $this->uploadDate = $uploadDate;
        } else {
            $this->uploadDate = new DateTime('now');
        }
        return $this;
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
     * @return Song
     */
    public function setFileName(?string $fileName): Song
    {
        $this->fileName = $fileName;
        return $this;
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
     * @return Song
     */
    public function setAlbumArtName(?string $albumArtName): Song
    {
        $this->albumArtName = $albumArtName;
        return $this;
    }

    /**
     * @return Artist|null
     */
    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    /**
     * @param Artist|object $artist
     * @return Song
     */
    public function setArtist(Artist $artist): Song
    {
        if (null === $artist) {
            $this->artist = null;
        } elseif ($artist instanceof Artist) {
            $this->artist = $artist;
        }else {
            throw new InvalidArgumentException('Artist is not valid');
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setPath(?string $uploadFieldName, ?array $file, bool $override = false): array
    {
        $file = parent::setPath($uploadFieldName, $file, $override);

        if ($uploadFieldName === 'albumArt') {
            $this->setAlbumArtName($file['path']);
        } elseif ($uploadFieldName === 'song') {
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
