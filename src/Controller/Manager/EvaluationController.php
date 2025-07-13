<?php

namespace App\Controller\Manager;

use App\Entity\Evaluation;
use App\Form\EvaluationType;
use App\Repository\EvaluationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_MANAGER')]
#[Route('/manager/evaluation')]
class EvaluationController extends AbstractController {

    public function __construct(
            private readonly Security $security,
    ) {
        
    }

    #[Route('/', name: 'app_evaluation_index', methods: ['GET'])]
    public function index(EvaluationRepository $repo): Response {
        return $this->render('manager/evaluation/index.html.twig', [
                    'evaluations' => $repo->findAll(),
                    'title' => "Liste des évaluations",
        ]);
    }

    #[Route('/new', name: 'app_evaluation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response {
        $evaluation = new Evaluation();
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentUser = $this->security->getUser();
            $evaluation->setEvaluateBy($currentUser);
            $em->persist($evaluation);
            $em->flush();
            $this->addFlash('success', 'Opération avec succèss !');
            return $this->redirectToRoute('app_evaluation_index');
        }

        return $this->render('manager/evaluation/new.html.twig', [
                    'form' => $form->createView(),
                    'title' => "Ajouter une évaluation",
        ]);
    }

    #[Route('/{id}', name: 'app_evaluation_show', methods: ['GET'])]
    public function show(Evaluation $evaluation): Response {
        return $this->render('manager/evaluation/show.html.twig', [
                    'evaluation' => $evaluation,
                    'title' => "Visualiser une évaluation",
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evaluation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evaluation $evaluation, EntityManagerInterface $em): Response {
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Opération avec succèss !');
            return $this->redirectToRoute('app_evaluation_index');
        }

        return $this->render('manager/evaluation/edit.html.twig', [
                    'form' => $form->createView(),
                    'title' => "Modifier une évaluation",
        ]);
    }

    #[Route('/{id}', name: 'app_evaluation_delete', methods: ['POST'])]
    public function delete(Request $request, Evaluation $evaluation, EntityManagerInterface $em): Response {
        if ($this->isCsrfTokenValid('delete' . $evaluation->getId(), $request->request->get('_token'))) {
            $em->remove($evaluation);
            $em->flush();
            $this->addFlash('success', 'Opération avec succèss !');
        }

        return $this->redirectToRoute('app_evaluation_index');
    }
}
