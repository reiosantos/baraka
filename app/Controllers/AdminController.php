<?php /** @noinspection MissingReturnTypeInspection */

namespace App\Controllers;


use App\Entity\Artist;
use App\Entity\Song;
use App\Utils\IRequest;
use Doctrine\ORM as ORM;
use Exception;
use RuntimeException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminController extends AbstractCtrl
{
    public $entityName = Song::class;
    public $dataHolder = 'songs';
    public $template = 'admin.html.twig';

    /**
     * {@inheritDoc}
     */
    public function post(IRequest $request)
    {
        $model = $request->get('model');
        switch ($model) {
            case 'artist':
                return $this->addArtist($request);
            case 'song':
                return $this->addSong($request);
            default:
                throw new RuntimeException("Could not process this request. Unknown model $model");
        }
    }

    /**
     * {@inheritDoc}
     */
    public function update(IRequest $request)
    {
        $model = $request->getModelFromRequest();

        switch ($model) {
            case Artist::class:
                return $this->updateArtist($request);
            case Song::class:
                return $this->updateSong($request);
            default:
                throw new RuntimeException("Could not process this request. Unknown model $model");
        }
    }

    /**
     * @param IRequest $request
     * @return string|null
     * @throws ORM\ORMException
     * @throws ORM\OptimisticLockException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function addArtist(IRequest $request): ?string
    {
        $name = $request->get('name');
        $details = $request->get('details');
        $photo = $request->getFile('photo');

        if (!$name || $name === null) {
            throw new RuntimeException('Name of the artist is required.');
        }
        $artist = new Artist();
        $artist->setName($name)
            ->setPhotoName($photo['name'])
            ->setDetails($details)
            ->setFiles($request->getFilesArray());

        $this->db->persist($artist);
        $this->db->flush($artist);
        return $this->render(null, ['success' => 'Artist Added']);
    }

    /**
     * @param IRequest $request
     * @return string|null
     * @throws LoaderError
     * @throws ORM\EntityNotFoundException
     * @throws ORM\ORMException
     * @throws ORM\OptimisticLockException
     * @throws ORM\TransactionRequiredException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    private function addSong(IRequest $request): ?string
    {
        $name = $request->get('name');
        $artistId = $request->get('artist');
        $albumArt = $request->getFile('albumArt');
        $songFile = $request->getFile('song');

        if (!$this->validateSong($songFile, $artistId)) {
            throw new RuntimeException('Could not Validate this request.');
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

        return $this->render(null, ['success' => 'Added new song to the collection']);
    }

    private function validateSong(?array $song, ?string $artistId): bool
    {
        if ($song === null || empty($song['name'])) {
            throw new RuntimeException('A song is required, but you did not upload any.');
        }
        if ($artistId === null) {
            throw new RuntimeException('An artist for this song is required, but you did not select any.');
        }
        return true;
    }

    /**
     * @param IRequest $request
     * @return string|null
     * @throws ORM\ORMException
     * @throws ORM\OptimisticLockException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function updateArtist(IRequest $request): ?string
    {
        $id = $request->getObjectPk();

        $artist = $this->db->find(Artist::class, $id);
        if ($artist === null) {
            return $this->render(null, ['error' => 'Artist not found']);
        }

        if ($request->getRequestMethod() === 'get') {
            return $this->render(null, ['artist' => $artist]);
        }

        $name = $request->get('name');
        $details = $request->get('details');
        $photo = $request->getFile('photo');

        if (!$name || $name === null) {
            throw new RuntimeException('Name of the artist is required.');
        }

        $artist->setName($name)->setDetails($details);

        if ($photo && $photo['name']) {
            $artist->setPhotoName($photo['name'])
                ->setFiles($request->getFilesArray());
        }

        $this->db->persist($artist);
        $this->db->flush($artist);
        return $this->render(null, ['success' => 'Artist Updated', 'artist' => $artist]);
    }

    /**
     * @param IRequest $request
     * @return string|null
     * @throws ORM\ORMException
     * @throws ORM\OptimisticLockException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function updateSong(IRequest $request): ?string
    {
        $id = $request->getObjectPk();

        $song = $this->db->find(Song::class, $id);
        if ($song === null) {
            return $this->render(null, ['error' => 'Song not found']);
        }

        if ($request->getRequestMethod() === 'get') {
            return $this->render(null, ['song' => $song]);
        }

        // TODO: Implement update on songs
        return $this->put($request);
    }
}
