<?php

namespace App\Controller\Rh;

use App\Entity\Mission;
use App\Form\MissionType;
use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_RH')]
#[Route('/rh/mission')]
final class MissionController extends AbstractController{
    #[Route(name: 'app_mission_index', methods: ['GET'])]
    public function index(MissionRepository $missionRepository): Response
    {
        return $this->render('rh/mission/index.html.twig', [
            'missions' => $missionRepository->findAll(),
            'title' => "Liste des missions",
        ]);
    }

    #[Route('/new', name: 'app_mission_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mission = new Mission();
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mission);
            $entityManager->flush();
            $this->addFlash('success', 'Opération avec succèss !');

            return $this->redirectToRoute('app_mission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rh/mission/new.html.twig', [
            'mission' => $mission,
            'form' => $form,
            'title' => "Ajouter mission",
        ]);
    }

    #[Route('/{id}', name: 'app_mission_show', methods: ['GET'])]
    public function show(Mission $mission): Response
    {
        return $this->render('rh/mission/show.html.twig', [
            'mission' => $mission,
            'title' => "Détail mission",
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mission $mission, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Opération avec succèss !');

            return $this->redirectToRoute('app_mission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rh/mission/edit.html.twig', [
            'mission' => $mission,
            'form' => $form,
            'title' => "Modifier mission",
        ]);
    }

    #[Route('/{id}', name: 'app_mission_delete', methods: ['POST'])]
    public function delete(Request $request, Mission $mission, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mission->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($mission);
            $entityManager->flush();
            $this->addFlash('success', 'Opération avec succèss !');
        }

        return $this->redirectToRoute('app_mission_index', [], Response::HTTP_SEE_OTHER);
    }
}
