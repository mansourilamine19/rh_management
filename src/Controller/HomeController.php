<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\MissionRepository;
use App\Repository\ContractRepository;
use App\Repository\UserRepository;
use App\Repository\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class HomeController extends AbstractController {

    public function __construct(
            private readonly Security $security,
            private readonly EntityManagerInterface $em,
    ) {
        
    }

    #[Route('/home', name: 'app_home')]
    public function index(
        UserRepository $userRepository,
        ContractRepository $contractRepository,
        MissionRepository $missionRepository,
        RegionRepository $regionRepository
    ): Response {
        $nbEmployes = $userRepository->count([]);
        $nbContrats = $contractRepository->count([]);
        $nbMissions = $missionRepository->count([]);
        $nbRegions  = $regionRepository->count([]);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'nb_employes' => $nbEmployes,
            'nb_contrats' => $nbContrats,
            'nb_missions' => $nbMissions,
            'nb_regions' => $nbRegions,
        ]);
    }
    
}
