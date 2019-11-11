<?php
namespace App\Controllers;

use App\Utils\IRequest;

interface Controller
{
    /**
     * @param IRequest $request
     * @return mixed
     */
    public function processRequest(IRequest $request): mixed;
}
