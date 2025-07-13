<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\MissionRepository;
use App\Repository\ContractRepository;
use App\Repository\UserRepository;
use App\Repository\RegionRepository;
use App\Repository\EvaluationRepository; // ✅ ajout du repository
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class HomeController extends AbstractController {

    public function __construct(
            private readonly Security $security,
            private readonly EntityManagerInterface $em,
    ) {
        
    }

    #[Route('/access-denied', name: 'access_denied')]
    public function accessDenied(): Response {
        return $this->render('security/access_denied.html.twig');
    }

    #[Route('/home', name: 'app_home')]
    public function index(
            UserRepository $userRepository,
            ContractRepository $contractRepository,
            MissionRepository $missionRepository,
            RegionRepository $regionRepository,
            EvaluationRepository $evaluationRepository
    ): Response {
        $nbEmployes = $userRepository->count([]);
        $nbContrats = $contractRepository->count([]);
        $nbMissions = $missionRepository->count([]);
        $nbRegions = $regionRepository->count([]);
        $evaluations = $evaluationRepository->findAll();

        return $this->render('home/index.html.twig', [
                    'controller_name' => 'HomeController',
                    'title' => "Dashboard RH",
                    'nb_employes' => $nbEmployes,
                    'nb_contrats' => $nbContrats,
                    'nb_missions' => $nbMissions,
                    'nb_regions' => $nbRegions,
                    'evaluations' => $evaluations,
        ]);
    }
}
