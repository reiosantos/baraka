<?php /** @noinspection MissingReturnTypeInspection */

namespace App\Controllers;


use App\Entity\Feedback;
use App\Utils\IRequest;

class FeedbackController extends AbstractCtrl
{
    public $entityName = Feedback::class;

    public function post(IRequest $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $location = $request->get('location');
        $message = $request->get('message');

        $feedback = new Feedback();
        $feedback->setUsername($name)
            ->setEmail($email)
            ->setLocation($location)
            ->setMessage($message)
            ->setDate();

        $this->db->persist($feedback);
        $this->db->flush($feedback);
        return $feedback;
    }

    public function put(IRequest $request)
    {
        // TODO: Implement put() method.
    }
}
