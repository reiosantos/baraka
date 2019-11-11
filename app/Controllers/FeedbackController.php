<?php
namespace App\Controllers;


use App\Utils\IRequest;

class FeedbackController implements Controller
{

    private $operation = null;

    /**
     * @param IRequest $request
     * @return mixed
     */
    public function processRequest(IRequest $request): mixed
    {
        $this->operation = $request;

    }
}
