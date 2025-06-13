<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\City;
use App\Repository\CityRepository;

#[Route('/city')]
final class CityController extends AbstractController {

    public function __construct(
            private readonly EntityManagerInterface $em,
            private readonly CityRepository $cityRepository,
    ) {
        
    }

    #[Route('/list-ajax', name: 'app_city_list')]
    public function listAjax(Request $request): Response {
        $region = $request->query->get("id");
        $cities = $this->cityRepository->findByRegion($region);
        //dd($cities);
        return $this->render('ajax/city/index.html.twig', [
                    'cities' => $cities,
        ]);
    }
}
