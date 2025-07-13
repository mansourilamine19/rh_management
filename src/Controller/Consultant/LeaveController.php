<?php

namespace App\Controller\Consultant;

use App\Entity\Leave;
use App\Form\LeaveType;
use App\Repository\LeaveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted('ROLE_CONSULTANT')]
#[Route('/consultant/leave')]
final class LeaveController extends AbstractController {

    public function __construct(
            private readonly Security $security,
    ) {
        
    }

    #[Route(name: 'app_leave_index', methods: ['GET'])]
    public function index(LeaveRepository $leaveRepository): Response {
        $currentUser = $this->security->getUser();
        $leaves = $leaveRepository->findByUser($currentUser);
        return $this->render('consultant/leave/index.html.twig', [
                    'title' => "Liste de mes congés",
                    'leaves' => $leaves,
        ]);
    }

    #[Route('/new', name: 'app_leave_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response {
        $leave = new Leave();
        $form = $this->createForm(LeaveType::class, $leave);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentUser = $this->security->getUser();
            $leave->setStatus("En attente vaidation");
            $leave->setUser($currentUser);
            $entityManager->persist($leave);
            $entityManager->flush();
            $this->addFlash('success', 'Opération avec succèss !');
            return $this->redirectToRoute('app_leave_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('consultant/leave/new.html.twig', [
                    'title' => "Demander un congé",
                    'leave' => $leave,
                    'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_leave_show', methods: ['GET'])]
    public function show(Leave $leave): Response {
        return $this->render('consultant/leave/show.html.twig', [
                    'title' => "Affichage détaillé du congé",
                    'leave' => $leave,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_leave_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Leave $leave, EntityManagerInterface $entityManager): Response {
        $form = $this->createForm(LeaveType::class, $leave);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Opération avec succèss !');
            return $this->redirectToRoute('app_leave_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('consultant/leave/edit.html.twig', [
                    'title' => "Modifier mon congé",
                    'leave' => $leave,
                    'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_leave_delete', methods: ['POST'])]
    public function delete(Request $request, Leave $leave, EntityManagerInterface $entityManager): Response {
        if ($this->isCsrfTokenValid('delete' . $leave->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($leave);
            $entityManager->flush();
            $this->addFlash('success', 'Opération avec succèss !');
        }

        return $this->redirectToRoute('app_leave_index', [], Response::HTTP_SEE_OTHER);
    }
}
