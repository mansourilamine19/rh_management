<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Locality;
use App\Repository\LocalityRepository;

#[Route('/locality')]
final class LocalityController extends AbstractController {

    public function __construct(
            private readonly EntityManagerInterface $em,
            private readonly LocalityRepository $localityRepository,
    ) {
        
    }

    #[Route('/list-ajax', name: 'app_locality_list')]
    public function listAjax(Request $request): Response {
        $city = $request->query->get("id");
        $localities = $this->localityRepository->findByCity($city);

        return $this->render('ajax/locality/index.html.twig', [
                    'localities' => $localities,
        ]);
    }
}
