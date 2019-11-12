<?php /** @noinspection MissingReturnTypeInspection */

namespace App\Controllers;

use App\Utils\IRequest;

interface Controller
{
    /**
     * @param IRequest $request
     * @return mixed
     */
    public function processRequest(IRequest $request);
    public function get(IRequest $request);
    public function post(IRequest $request);
    public function put(IRequest $request);
    public function delete(IRequest $request);
}
