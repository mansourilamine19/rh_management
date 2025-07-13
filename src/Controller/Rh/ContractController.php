<?php

namespace App\Controller\Rh;

use App\Entity\Contract;
use App\Form\ContractType;
use App\Repository\ContractRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_RH')]
#[Route('/rh/contract')]
final class ContractController extends AbstractController {

    #[Route(name: 'app_contract_index', methods: ['GET'])]
    public function index(ContractRepository $contractRepository): Response {
        return $this->render('rh/contract/index.html.twig', [
                    'contracts' => $contractRepository->findAll(),
                    'title' => "Liste de contrats",
        ]);
    }

    #[Route('/new', name: 'app_contract_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response {
        $contract = new Contract();
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contract);
            $entityManager->flush();
            $this->addFlash('success', 'Opération avec succèss !');

            return $this->redirectToRoute('app_contract_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rh/contract/new.html.twig', [
                    'contract' => $contract,
                    'form' => $form,
                    'title' => "Affecter contrat",
        ]);
    }

    #[Route('/{id}', name: 'app_contract_show', methods: ['GET'])]
    public function show(Contract $contract): Response {
        return $this->render('rh/contract/show.html.twig', [
                    'contract' => $contract,
                    'title' => "Affichage contrat",
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contract_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contract $contract, EntityManagerInterface $entityManager): Response {
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Opération avec succèss !');

            return $this->redirectToRoute('app_contract_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rh/contract/edit.html.twig', [
                    'contract' => $contract,
                    'form' => $form,
                    'title' => "Modifier contrat",
        ]);
    }

    #[Route('/{id}', name: 'app_contract_delete', methods: ['POST'])]
    public function delete(Request $request, Contract $contract, EntityManagerInterface $entityManager): Response {
        if ($this->isCsrfTokenValid('delete' . $contract->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($contract);
            $entityManager->flush();
            $this->addFlash('success', 'Opération avec succèss !');
        }

        return $this->redirectToRoute('app_contract_index', [], Response::HTTP_SEE_OTHER);
    }
}
