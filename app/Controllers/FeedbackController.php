<?php /** @noinspection MissingReturnTypeInspection */

namespace App\Controllers;


use App\Entity\Feedback;
use App\Utils\IRequest;

class FeedbackController extends AbstractCtrl
{
    public $entityName = Feedback::class;
    public $dataHolder = 'feedback';
    public $template = 'feedback.html.twig';

    public function post(IRequest $request)
    {
        $this->validateToken($request);
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

        return $this->render(null, ['success' => 'Thanks for your feedback.']);
    }
}
