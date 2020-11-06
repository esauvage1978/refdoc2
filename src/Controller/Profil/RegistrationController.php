<?php

declare(strict_types=1);

namespace App\Controller\Profil;

use App\Controller\AbstractGController;
use App\Entity\User;
use App\Event\UserRegistrationEvent;
use App\Form\Profil\RegistrationFormType;
use App\Mail\UserMail;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class RegistrationController extends AbstractGController
{
    /**
     * @Route("/registration", name="registration")
     */
    public function registrerAction(
        Request $request,
        UserManager $userManager,
        EventDispatcherInterface $dispatcher
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($userManager->save($user)) {
                $this->addFlash(self::SUCCESS, 'Compte créé avec succès ! Merci de valider votre compte à partir du mail envoyé !');

                $event = new UserRegistrationEvent($user);
                $dispatcher->dispatch($event, UserRegistrationEvent::NAME);

                return $this->redirectToRoute('user_login');
            }

            $this->addFlash(self::DANGER, self::MSG_CREATE_ERROR . $userManager->getErrors($user));
        }

        return $this->render('profil/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @route("/email/{token}", name="profil_email_validated")
     */
    public function profilEmailValidatedAction(
        string $token,
        UserRepository $userRepository,
        UserManager $userManager
    ): Response {
        $user = $userRepository->findOneBy(['emailValidatedToken' => $token]);

        if ($user === null) {
            $this->addFlash('warning', 'L\'adresse d\'activation est incorrecte!');
        } else {
            $userManager->validateEmail($user);

            if ($userManager->save($user)) {
                $this->addFlash('success', 'Votre compte est activé. Vous pouvez vous connecter!');
            } else {
                $this->addFlash('danger', 'Echec de la mise à jour' . $userManager->getErrors($user));
            }
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/sendmail/emailvalidated", methods={"GET"}, name="profil_sendmail_email_validated")
     */
    public function sendmailActivationAction(UserMail $mail): Response
    {
        $user = $this->getUser();
        $mail->send($user, UserMail::VALIDATE, 'Validation de l\'email');
        $this->addFlash('success', 'Le mail est envoyé, merci de consulter votre messagerie.');

        return $this->redirectToRoute('home');
    }
}
