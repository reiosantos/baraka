<?php /** @noinspection MissingReturnTypeInspection */

namespace App\Controllers;

use App\Entity\Artist;
use App\Utils\IRequest;
use RuntimeException;

class ArtistController extends AbstractCtrl
{
    public $entityName = Artist::class;
    public $dataHolder = 'artists';
    public $template = 'artists.html.twig';

    public function post(IRequest $request)
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
        return $this->render();
    }
}
