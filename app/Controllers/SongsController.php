<?php /** @noinspection MissingReturnTypeInspection */

namespace App\Controllers;

use App\Entity\Artist;
use App\Entity\Song;
use App\Utils\IRequest;
use Doctrine\ORM as ORM;
use Error;
use RuntimeException;

class SongsController extends AbstractCtrl
{
    public $entityName = Song::class;

    private function validateSong(?array $song, ?string $artistId): bool {
        if ($song === null) {
            throw new RuntimeException('A song is required, but you did not upload any.');
        }
        if ($artistId === null) {
            throw new RuntimeException('An artist for this song is required, but you did not select any.');
        }
        return true;
    }

    public function post(IRequest $request)
    {
        $name = $request->get('name');
        $artistId = $request->get('artist');
        $albumArt = $request->getFile('albumArt');
        $songFile = $request->getFile('song');

        if (!$this->validateSong($songFile, $artistId)){
            throw new Error('Could not Validate this request.');
        }
        $artist = $this->db->find(Artist::class, $artistId);
        if ($artist === null) {
            throw new ORM\EntityNotFoundException('Selected Artist Not Found');
        }
        if ($name === null || trim($name) === '') {
            $name = $songFile['name'];
        }
        $song = new Song();
        $song->setName($name)
            ->setArtist($artist)
            ->setUploadDate()
            ->setFileName($songFile['name'])
            ->setAlbumArtName($albumArt['name'])
            ->setFiles($request->getFilesArray());

        $this->db->persist($song);
        $this->db->flush($song);
        return $song;
    }

    public function put(IRequest $request)
    {
        // TODO: Implement put() method.
    }

    /**
     * @param IRequest $request
     * @throws ORM\EntityNotFoundException
     * @throws ORM\ORMException
     * @throws ORM\OptimisticLockException
     * @throws ORM\TransactionRequiredException
     */
    public function download(IRequest $request): void
    {
        $id = $request->getObjectPk();
        $song = $this->db->find($this->entityName, $id);

        if ($song === null || !file_exists($song->getFileName()) || !is_file($song->getFileName())) {
            throw new ORM\EntityNotFoundException('Could not locate the requested song. Try again.');
        }

        $fileName = $song->getFileName();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $song->getDownloadFileName($fileName));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileName));
        ob_clean();
        flush();
        readfile($fileName);
    }
}
