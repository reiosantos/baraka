<?php /** @noinspection MissingReturnTypeInspection */

namespace App\Controllers;

use App\Entity\Song;
use App\Utils\IRequest;
use Doctrine\ORM as ORM;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SongController extends AbstractCtrl
{
    public $entityName = Song::class;
    public $dataHolder = 'songs';
    public $template = 'songs.html.twig';

    public function get(IRequest $request)
    {
        $search = $request->get('search');
        if ($search) {
            $data = $this->db->searchSong($this->entityName, $search);
            return $this->render($this->template, [$this->dataHolder => $data]);
        }
        return parent::get($request);
    }

    /**
     * @param IRequest $request
     * @return string
     * @throws ORM\EntityNotFoundException
     * @throws ORM\ORMException
     * @throws ORM\OptimisticLockException
     * @throws ORM\TransactionRequiredException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function download(IRequest $request): string
    {
        $id = $request->getObjectPk();
        $song = $this->db->find($this->entityName, $id);

        if ($song === null || !file_exists($song->getFileName()) || !is_file($song->getFileName())) {
            throw new ORM\EntityNotFoundException('Could not locate the requested song. Try again.');
        }

        $fileName = $song->getFileName();

        header('Pragma: public');
        header('Expires: 0');
        header('Content-Description: File Transfer');
        header("Content-type: application/octet-stream");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: public');
        header('Content-Disposition: attachment; filename=' . $song->getDownloadFileName($fileName));
        header('Content-Length: ' . filesize($fileName));
        //ob_clean();
        //flush();
        readfile($fileName);

        return $this->render();
    }
}
