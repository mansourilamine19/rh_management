<?php

namespace App\Controller\Manager;

use App\Entity\Leave;
use App\Form\LeaveType;
use App\Repository\LeaveRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_MANAGER')]
#[Route('/manager/leave')]
final class LeaveController extends AbstractController {

    public function __construct(
            private readonly Security $security,
            private readonly UserRepository $userRepository,
            private readonly EntityManagerInterface $em,
    ) {
        
    }

    #[Route(name: 'app_manager_leave_index', methods: ['GET'])]
    public function index(LeaveRepository $leaveRepository): Response {
        $currentUser = $this->security->getUser();
        $consultants = $this->userRepository->findByManager($currentUser);
        $listLeaves = [];
        foreach ($consultants as $consultant) {
            $leaves = $leaveRepository->findByUser($consultant);
            if (!empty($leaves)){
                foreach($leaves as $leave){
                $listLeaves[] = $leave;
                }
            }
        }
        return $this->render('manager/leave/index.html.twig', [
                    'title' => "Liste de mes congés à valider",
                    'leaves' => $listLeaves,
        ]);
    }

    #[Route('/{id}', name: 'app_manager_leave_response', methods: ['POST'])]
    public function responseLeave(Request $request, Leave $leave, EntityManagerInterface $entityManager): Response {
        $data = $request->request->all();
        if ($this->isCsrfTokenValid('validate' . $leave->getId(), $request->getPayload()->getString('_token'))) {
            $leave->setStatus($data["status"]);
            $entityManager->flush();
            $this->addFlash('success', 'Opération avec succèss !');
        }

        return $this->redirectToRoute('app_manager_leave_index', [], Response::HTTP_SEE_OTHER);
    }
}
