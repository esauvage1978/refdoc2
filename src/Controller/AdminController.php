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
                'icon'=> 'fa fa-users text-p-dark' 
            ],
            [
                'name' => 'Utilisateurs avec notification',
                'route' => 'notification_users_subscription',
                'content'=> 'Consultation des utilisateurs valide et ayant accepté de recevoir les notifications',
                'smallcontent'=>'',
                'icon'=> 'fa fa-users text-p-dark' 
            ],
            [
                'name' => 'Macro processus',
                'route' => 'admin_mprocess_list',
                'content' => 'Gestion des macro-processus et des intervenants',
                'smallcontent' => '',
                'icon' => 'fas fa-sitemap text-p-dark' 
            ],
            [
                'name' => 'Processus',
                'route' => 'admin_process_list',
                'content' => 'Gestion des processus et des intervenants',
                'smallcontent' => '',
                'icon' => 'fas fa-square text-p-dark'
            ],
            [
                'name' => 'Type de porte-document',
                'route' => 'admin_category_list',
                'content' => 'Gestion des types de porte-document',
                'smallcontent' => '<ul><li>Consigne,</li><li> note de processus,</li><li> procédure,</li><li> mode opératoire,</li><li>...</li></ul>',
                'icon' => 'fas fa-clipboard-list text-p-dark'
            ]                                          
        ];

        $list_entries = [
            
            [
                'name' => 'Permissions sur les macro processus',
                'route' => 'admin_mprocess_list_permission',
                'content' => 'Consultation de la liste des utilisateurs  ',
                'smallcontent' => '<ul><li>Valideur : agent de direction,</li><li>Valideur : Manager stratégique</li><li>Contributeur</li></ul>',
                'icon' => 'fas fa-sitemap text-p-dark'
            ],
            [
                'name' => 'Permissions sur les processus',
                'route' => 'admin_process_list_permission',
                'content' => 'Consultation de la liste des utilisateurs  ',
                'smallcontent' => '<ul><li>Valideur</li><li>Contributeur</li></ul>',
                'icon' => 'fas fa-square text-p-dark'
            ]
            ];

        
        return $this->render('admin/index.html.twig', [
            'general_entries' => $general_entries,
            'list_entries' => $list_entries
        ]);
    }
}
