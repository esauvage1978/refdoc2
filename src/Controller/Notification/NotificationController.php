<?php

declare(strict_types=1);

namespace App\Controller\Notification;

use App\Repository\UserRepository;
use App\Controller\AbstractGController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class NotificationController extends AbstractGController
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
