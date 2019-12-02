<?php /** @noinspection MissingReturnTypeInspection */

namespace App\Controllers;

use App\Entity\Artist;

class ArtistController extends AbstractCtrl
{
    public $entityName = Artist::class;
    public $dataHolder = 'artists';
    public $template = 'artists.html.twig';
}
