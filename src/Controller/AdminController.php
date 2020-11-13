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
                'name' => 'Utilisateurs',
                'route' => 'user_list',
                'content'=> 'Gestion des utilisateurs',
                'smallcontent'=>'réservé à l\'administrateur',
                'icon'=>'fa fa-users text-info' 
            ],
            [
                'name' => 'Macro processus',
                'route' => 'admin_mprocess_list',
                'content' => 'Gestion des macro-processus et des intervenants',
                'smallcontent' => '',
                'icon' => 'fas fa-sitemap text-info' 
            ],
            [
                'name' => 'Processus',
                'route' => 'admin_process_list',
                'content' => 'Gestion des processus et des intervenants',
                'smallcontent' => '',
                'icon' => 'fas fa-square text-info'
            ]                            
        ];

        $list_entries = [
            
            [
                'name' => 'Permissions sur les macro processus',
                'route' => 'admin_mprocess_list_permission',
                'content' => 'Consultation de la liste des utilisateurs ayant le profil valideur (agent de direction, Manager stratégique) et contributeur ',
                'smallcontent' => '',
                'icon' => 'fas fa-sitemap text-info'
            ],
            [
                'name' => 'Permissions sur les processus',
                'route' => 'admin_process_list_permission',
                'content' => 'Consultation de la liste des utilisateurs ayant le profil valideur et contributeur ',
                'smallcontent' => '',
                'icon' => 'fas fa-square text-info'
            ]
            ];

        
        return $this->render('admin/index.html.twig', [
            'general_entries' => $general_entries,
            'list_entries' => $list_entries
        ]);
    }
}
