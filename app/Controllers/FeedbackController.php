<?php /** @noinspection MissingReturnTypeInspection */

namespace App\Controllers;


use App\Entity\Feedback;
use App\Utils\IRequest;
use RuntimeException;

class FeedbackController extends AbstractCtrl
{
    public $entityName = Feedback::class;
    public $dataHolder = 'feedback';
    public $template = 'feedback.html.twig';

    public function post(IRequest $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $location = $request->get('location');
        $message = $request->get('message');
        if (!$name) {
            throw new RuntimeException('Name is required');
        }
        if (!$email) {
            throw new RuntimeException('Valid email is required');
        }
        if (!$message) {
            throw new RuntimeException('Empty message cannot be sent.');
        }

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
