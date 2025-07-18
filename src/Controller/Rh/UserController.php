<?php

namespace App\Controller\Rh;

use App\Entity\User;
use App\Form\User1Type;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_RH')]
#[Route('/rh/user')]
final class UserController extends AbstractController {

    public function __construct(
            private readonly EntityManagerInterface $em,
            private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {
        
    }

    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response {
        return $this->render('rh/user/index.html.twig', [
                    'users' => $userRepository->findAll(),
                    'title' => 'Liste utilisateurs',
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response {
        $user = new User();
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form->get('cv')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid() . '.' . $file->guessExtension();

                try {
                    $file->move(
                            $this->getParameter('uploads_directory'),
                            $newFilename
                    );
                    $user->setCv($newFilename);
                } catch (FileException $e) {
                    // Handle error if needed
                }
            }
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $form["tel"]->getData()));
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Opération avec succèss !');
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rh/user/new.html.twig', [
                    'user' => $user,
                    'form' => $form,
                    'title' => 'Ajout utilisateur',
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response {
        return $this->render('rh/user/show.html.twig', [
                    'user' => $user,
                    'title' => 'Détail utilisateur',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response {
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form->get('cv')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid() . '.' . $file->guessExtension();

                try {
                    $file->move(
                            $this->getParameter('uploads_directory'),
                            $newFilename
                    );
                    $user->setCv($newFilename);
                } catch (FileException $e) {
                    // Handle error if needed
                }
            }
            $entityManager->flush();
            $this->addFlash('success', 'Opération avec succèss !');
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rh/user/edit.html.twig', [
                    'user' => $user,
                    'form' => $form,
                    'title' => 'Modification utilisateur',
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'Opération avec succèss !');
        }
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
