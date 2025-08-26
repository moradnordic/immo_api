<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
final class UserController extends AbstractController
{

    #[Route('/user', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $agents = array_filter($userRepository->findAll(), function ($user) {
                return in_array('ROLE_AGENCE', $user->getRoles());
            });
        }else{
            $user = $this->getUser(); // Get the logged-in user
            $agents = $userRepository->findBy(['agence' => $user->getAgence()]); // Get only the agents of the logged-in Agence
        }

        return $this->render('user/index.html.twig', [
            'users' => $agents,
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $loggedInUser = $this->getUser();
        $user = new User();

        // Assign roles based on the creator's role
        if (in_array('ROLE_ADMIN', $loggedInUser->getRoles())) {
            $user->setRoles(['ROLE_AGENCE']);
        } elseif (in_array('ROLE_AGENCE', $loggedInUser->getRoles())) {
            $user->setRoles(['ROLE_AGENT']);
            $user->setAgence($loggedInUser->getAgence());
        } else {
            throw $this->createAccessDeniedException('You do not have permission to create a user.');
        }

        $form = $this->createForm(UserType::class, $user,[
            'current_user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Retrieve the plain password from the form
            $plainPassword = $form->get('password')->getData();

            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            } else {
                throw new \Exception('Password cannot be empty');
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User created successfully.');

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }



    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user,[
            'current_user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Retrieve the plain password from the form
            $plainPassword = $form->get('password')->getData();

            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            } else {
                throw new \Exception('Password cannot be empty');
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User created successfully.');

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
