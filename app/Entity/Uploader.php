<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class Uploader
{
    /**
     * @var array [name => $_FILES]
     */
    public $files = null;

    public function getUploadRootDir(): string
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'../'.$this->getUploadDir();
    }

    public function getUploadDir(): string
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/documents/';
    }

    /**
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @return mixed
     */
    public function setFiles($files): void
    {
        foreach ($files as $name => $file) {
            $this->files[$name] = $file;
        }
    }

    /**
     * @param string|null $uploadFieldName
     * @param mixed $file
     * @param bool $override
     * @return mixed
     */
    public function setPath(?string $uploadFieldName, mixed $file, bool $override = false): mixed {
        if (!isset($file['path']) || $override) {
            $filename = sha1(uniqid(mt_rand(), true));
            $file['path'] = $this->getUploadRootDir() . $filename . '-' . $file['name'];
            if ($uploadFieldName !== null) {
                $file['uploadFieldName'] = $uploadFieldName;
            }
        }
        return $file;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload(): void
    {
        $fls = [];
        if (null !== $this->getFiles()) {
            // do whatever you want to generate a unique name
            foreach ($this->getFiles() as $name => $file) {
                $fls[$name] = $this->setPath($name, $file);
            }
            $this->files = $fls;
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload(): void
    {
        if (null === $this->getFiles()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        foreach ($this->getFiles() as $name => $file) {
            move_uploaded_file($file['tmp_name'], $file['path']);
        }

        $this->files = null;
    }

    /**
     * @ORM\PostRemove()
     */
    abstract public function removeUpload(): void;
}
