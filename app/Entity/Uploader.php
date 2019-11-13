<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use RuntimeException;

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
        $dir = dirname(__DIR__) . '/' .$this->getUploadDir();
        if (!file_exists($dir) && !mkdir($dir, null, true) && !is_dir($dir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
        return $dir;
    }

    public function getWebPath(string $name): string
    {
        return explode(dirname(__DIR__, 2), $name)[1];
    }

    public function getDownloadFileName(string $fullPath): string
    {
        return explode('-', $fullPath, 2)[1];
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
     * @param array $files
     * @return mixed
     */
    public function setFiles(array $files): void
    {
        foreach ($files as $name => $file) {
            if ($file['name']) {
                $this->files[$name] = $file;
            }
        }
    }

    /**
     * @param string|null $uploadFieldName
     * @param array $file
     * @param bool $override
     * @return mixed
     */
    public function setPath(?string $uploadFieldName, array $file, bool $override = false): array {
        if (!isset($file['path']) || $override) {
            $filename = sha1(uniqid(mt_rand(), true));
            $file['path'] = $this->getUploadRootDir() . $filename . '-' . $file['name'];
            $file['path'] = str_replace(' ', '-', $file['path']);
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
