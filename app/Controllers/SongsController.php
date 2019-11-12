<?php /** @noinspection MissingReturnTypeInspection */

namespace App\Controllers;

use App\Entity\Song;
use App\Utils\IRequest;

class SongsController extends AbstractCtrl
{
    public $entityName = Song::class;

    public function post(IRequest $request)
    {
        // TODO: Implement post() method.
    }

    public function put(IRequest $request)
    {
        // TODO: Implement put() method.
    }
}
