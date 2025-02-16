<?php

namespace App\Controller;

use App\Entity\Blogs;
use App\Form\BlogsType;
use App\Repository\BlogsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/blogs')]
final class BlogsController extends AbstractController
{
    #[Route(name: 'app_blogs_index', methods: ['GET'])]
    public function index(BlogsRepository $blogsRepository,Security $security): Response
    {
        $user = $security->getUser();
        return $this->render('blogs/index.html.twig', [
            'blogs' => $blogsRepository->findAll(),
            'user' => $user
        ]);
    }

    #[Route('/new', name: 'app_blogs_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security
    ): Response
    {
        $blog = new Blogs();
        $form = $this->createForm(BlogsType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $videoFile */
            $videoFile = $form->get('videoFile')->getData();

            if ($videoFile) {
                $newFilename = uniqid().'.'.$videoFile->guessExtension();

                // Move the file to the directory where videos are stored
                $videoFile->move(
                    $this->getParameter('videos_directory'),
                    $newFilename
                );

                // Update the video property with the file path
                $blog->setVideo('/uploads/videos/'.$newFilename);
            }

            // Set the current date and time
            $blog->setCreatedAt(new \DateTimeImmutable());
            // Set the current user as author
            $currentUser = $security->getUser();
            $blog->setAuthor($currentUser);
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('app_blogs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blogs/new.html.twig', [
            'blog' => $blog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blogs_show', methods: ['GET'])]
    public function show(Blogs $blog,Security $security): Response
    {
        return $this->render('blogs/show.html.twig', [
            'blog' => $blog,
            'user' => $security->getUser()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_blogs_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Blogs $blog, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlogsType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('app_blogs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blogs/edit.html.twig', [
            'blog' => $blog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blogs_delete', methods: ['POST'])]
    public function delete(Request $request, Blogs $blog, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blog->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($blog);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_blogs_index', [], Response::HTTP_SEE_OTHER);
    }
}
