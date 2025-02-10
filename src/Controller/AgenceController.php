<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Form\AgenceType;
use App\Repository\AgenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/agence')]
final class AgenceController extends AbstractController
{
    #[Route(name: 'app_agence_index', methods: ['GET'])]
    public function index(AgenceRepository $agenceRepository, Security $security): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
            $agences = $agenceRepository->findAll(); // Admins get all agencies
        } else {
            $user = $security->getUser();
            $agences = $user->getAgence() ? [$user->getAgence()] : []; // Non-admins get only their agency
        }

        return $this->render('agence/index.html.twig', [
            'agences' => $agences,
        ]);
    }

    #[Route('/new', name: 'app_agence_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'exception si un problème survient lors du téléchargement
                }

                $agence->setImageFilename($newFilename);
            }

            $entityManager->persist($agence);
            $entityManager->flush();

            return $this->redirectToRoute('app_agence_index');
        }

        return $this->render('agence/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_agence_show', methods: ['GET'])]
    public function show(Agence $agence): Response
    {
        return $this->render('agence/show.html.twig', [
            'agence' => $agence,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_agence_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Agence $agence, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the file upload
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                // Generate a unique name for the file
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    // Delete old image if exists
                    if ($agence->getImageFilename()) {
                        $oldFilePath = $this->getParameter('images_directory').'/'.$agence->getImageFilename();
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }

                    // Move the file to the directory where images are stored
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );

                    // Update the 'imageFilename' property
                    $agence->setImageFilename($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image');
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'L\'agence a été modifiée avec succès');
            return $this->redirectToRoute('app_agence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('agence/edit.html.twig', [
            'agence' => $agence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_agence_delete', methods: ['POST'])]
    public function delete(Request $request, Agence $agence, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agence->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($agence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_agence_index', [], Response::HTTP_SEE_OTHER);
    }
}
