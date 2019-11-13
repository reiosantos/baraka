<?php /** @noinspection MissingReturnTypeInspection */

namespace App\Controllers;

use App\Entity\Artist;
use App\Entity\Song;
use App\Utils\IRequest;
use Doctrine\ORM as ORM;
use Doctrine\ORM\EntityNotFoundException;
use Error;
use Exception;
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

    /**
     * @param IRequest $request
     * @return Song
     * @throws ORM\ORMException
     * @throws ORM\EntityNotFoundException
     * @throws ORM\OptimisticLockException
     * @throws ORM\TransactionRequiredException
     * @throws Exception
     */
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
            throw new EntityNotFoundException('Selected Artist Not Found');
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
}
