<?php

declare(strict_types=1);

namespace App\Controller\Profil;

use App\Controller\AbstractGController;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use function json_encode;

class AvatarController extends AbstractGController
{
    public const DOMAINE = 'profil';

    /**
     * @Route("/profil/avatar", name="avatar")
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, UserRepository $userRepository): Response
    {
        return $this->render(self::DOMAINE . '/avatar.html.twig');
    }

    /**
     * @Route("/profil/avatar/update", name="avatar_update")
     */
    public function ajaxAction(Request $request, UserManager $userManager): Response
    {
        /**
         * var $user User
         */
        $user = $this->getUser();

        /* on récupère la valeur envoyée par la vue */
        $image = $request->request->get('dataImg');

        $userManager->changeAvatar($user, $image);

        $response = new Response(json_encode(['retour' => 'Avatar mis à jour']));

        $response->headers->set('Content-Type', 'application/json');

        $this->addFlash(self::SUCCESS, self::MSG_MODIFY);

        return $response;
    }
}
