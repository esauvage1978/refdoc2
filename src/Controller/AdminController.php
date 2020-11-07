<?php

declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     * @IsGranted("ROLE_USER")
     */
    public function index()
    {
        $general_entries = [
            [
                'name' => 'Gestion des utilisateurs',
                'route' => 'user_list',
            ]
        ];

        
        return $this->render('admin/index.html.twig', [
            'general_entries' => $general_entries
        ]);
    }
}
