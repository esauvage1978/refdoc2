<?php

declare(strict_types=1);

namespace App\Controller\Profil;

use App\Controller\AbstractGController;
use App\Form\Profil\PasswordChangeFormType;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordChangeController extends AbstractGController
{
    /**
     * @Route("/password-change", name="password_change")
     */
    public function changePasswordAction(
        Request $request,
        UserRepository $userRepository,
        UserManager $userManager,
        UserPasswordEncoderInterface $encoder
    ): Response {
        $user = $this->getUser();

        $form = $this->createForm(PasswordChangeFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($userManager->checkPassword($user, $form->getData()['plainPasswordOld'])) {
                $userManager->initialisePasswordChange(
                    $user,
                    $form->getData()['plainPassword'],
                    $form->getData()['plainPasswordConfirmation']
                );

                $userManager->encodePassword($user);

                $userManager->save($user);

                $this->addFlash(self::SUCCESS, self::MSG_MODIFY);

                return $this->redirectToRoute('profil');
            }

            $this->addFlash(self::DANGER, 'L\'ancien mot de passe est incorrect.');
        }

        return $this->render('profil/passwordChange.html.twig', [self::FORM => $form->createView()]);
    }
}
