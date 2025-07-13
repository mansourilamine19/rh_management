<?php

namespace App\Controller\Consultant;

use App\Entity\Skill;
use App\Form\SkillType;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_CONSULTANT')]
#[Route('/consultant/skill')]
class SkillController extends AbstractController {

    #[Route('/', name: 'app_skill_index', methods: ['GET'])]
    public function index(SkillRepository $skillRepository): Response {
        return $this->render('consultant/skill/index.html.twig', [
                    'skills' => $skillRepository->findAll(),
                    'title' => "La liste des compétences",
        ]);
    }

    #[Route('/new', name: 'app_skill_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response {
        $skill = new Skill();
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($skill);
            $entityManager->flush();
            $this->addFlash('success', 'Opération avec succèss !');
            return $this->redirectToRoute('app_skill_index');
        }

        return $this->render('consultant/skill/new.html.twig', [
                    'skill' => $skill,
                    'form' => $form,
                    'title' => "Ajouter une compétence",
        ]);
    }

    #[Route('/{id}', name: 'app_skill_show', methods: ['GET'])]
    public function show(Skill $skill): Response {
        return $this->render('consultant/skill/show.html.twig', [
                    'skill' => $skill,
                    'title' => "Viusaliser une compétence",
        ]);
    }

    #[Route('/{id}/edit', name: 'app_skill_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Skill $skill, EntityManagerInterface $entityManager): Response {
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Opération avec succèss !');
            return $this->redirectToRoute('app_skill_index');
        }

        return $this->render('consultant/skill/edit.html.twig', [
                    'skill' => $skill,
                    'form' => $form,
                    'title' => "Modifier une compétence",
        ]);
    }

    #[Route('/{id}', name: 'app_skill_delete', methods: ['POST'])]
    public function delete(Request $request, Skill $skill, EntityManagerInterface $entityManager): Response {
        if ($this->isCsrfTokenValid('delete' . $skill->getId(), $request->request->get('_token'))) {
            $entityManager->remove($skill);
            $entityManager->flush();
            $this->addFlash('success', 'Opération avec succèss !');
        }

        return $this->redirectToRoute('app_skill_index');
    }
}
