<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class HomeController extends AbstractController {

    public function __construct(
            private readonly Security $security,
            private readonly EntityManagerInterface $em,
    ) {
        
    }

    #[Route('/home', name: 'app_home')]
    public function index(): Response {
        return $this->render('home/index.html.twig', [
                    'controller_name' => 'HomeController',
        ]);
    }
    
}
