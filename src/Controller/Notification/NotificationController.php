<?php

declare(strict_types=1);

namespace App\Controller\Notification;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    /**
     * @Route("/notification/{id}", name="notification")
     */
    public function index(string $id)
    {
        // id =1
        switch ($id) {
            case '1':
                return $this->redirectToRoute('user_login');

                break;
            case '2':
                return $this->redirectToRoute('profil_sendmail_email_validated');

                break;
        }

        return null;
    }
}
