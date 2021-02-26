<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Backpack;
use App\Entity\Mailer;
use App\Mail\MailerMail;
use App\Manager\MailerManager;
use App\Form\Mailer\MailerFormBackpackType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailerController extends AbstractController
{
    /**
     * @Route("/mailer/composer/{id}", name="mailer_backpack_composer")
     */
    public function mailerBackpackComposer(
        Request $request,
        Backpack $item,
        MailerManager $manager,
        MailerMail $mailerMail
    ) {
        $form = $this->createForm(MailerFormBackpackType::class, ['data' => $item->getId()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mailer = $manager->initialiseMailer($form->getData());
            $users= $manager->getUsersEmailTo();

            if (is_a($mailer, Mailer::class) && $users->count()>0) {
                
                $mailer->setBackpack($item);
                
                foreach($users as $user){
                    $mailerMail->send($user,$mailer,MailerMail::BACKPACK,$mailer->getSubject());
                }

                $manager->save($mailer);

                $this->addFlash(AbstractGController::SUCCESS, 'Message envoyé');
            } else {
                $this->addFlash(AbstractGController::DANGER, 'Le mail n\'est pas envoyé. La cause probable est une absence de destinataire');
            }
        }
        return $this->render('mailer/composer_backpack.html.twig', [
            'item' => $item,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/mailer/{id}", name="mailer_backpack_history")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mailierBackpackHistory(
        Backpack $item
    ) {
        return $this->render('mailer/history_backpack.html.twig', [
            'item' => $item,
        ]);
    }


}
