<?php

declare(strict_types=1);

namespace App\Controller\Profil;

use App\Entity\User;
use function assert;
use App\Manager\UserManager;
use App\Form\Profil\ProfilType;
use App\Controller\AbstractGController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ProfilController extends AbstractGController
{
    /**
     * @Route("/profil", name="profil")
     * @return Response
     * @var UserManager $userManager
     * @var Request $request
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function profileHomeAction(
        Request $request,
        UserManager $manager 
         ): Response
    {
        $user = $this->getUser();
        $oldUserMail = (clone $user)->getEmail();
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($manager->save($user, $oldUserMail)) {
                $this->addFlash(self::SUCCESS, self::MSG_MODIFY);
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $manager->getErrors($user));
            }
        }

        return $this->render('profil/index.html.twig', [
            'item' => $user,
            'form' => $form->createView()
        ]);
    }
}
