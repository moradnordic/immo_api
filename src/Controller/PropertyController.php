<?php

namespace App\Controller;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\AgenceRepository;
use App\Repository\PropertyRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/property')]
final class PropertyController extends AbstractController
{
    #[Route(name: 'app_property_index', methods: ['GET'])]
    public function index(PropertyRepository $propertyRepository, Security $security): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
            $properties = $propertyRepository->findAll(); // Admin gets all properties
        } else {
            $user = $security->getUser();
            $properties = $propertyRepository->findBy(['agence' => $user->getAgence()]); // Non-admin gets only their agency's properties
        }

        return $this->render('property/index.html.twig', [
            'properties' => $properties,
        ]);
    }


    #[Route('/new', name: 'app_property_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {

        $property = new Property();
        $user = $security->getUser(); // Get the currently logged-in user

        // Assuming the `User` entity has a relation to `Agence`
        $agence = $user->getAgence(); // Get the user's associated agence

        $form = $this->createForm(PropertyType::class, $property, [
            'user' => $user,
            'agence' => $agence, // Pass the user's agence to the form
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle uploaded images
            $images = $form->get('imageFiles')->getData(); // Use 'imageFiles' from the form
            $property->setCreatedAt(new DateTimeImmutable());
            $property->setCountry('maroc');

            if ($images) {
                foreach ($images as $image) {
                    /** @var UploadedFile $image */
                    $newFilename = uniqid() . '.' . $image->guessExtension();

                    // Move the file to the directory where images are stored
                    $image->move(
                        $this->getParameter('images_directory'), // Check this parameter
                        $newFilename
                    );

                    // Add the new filename to the img array
                    $img = $property->getImg();
                    $img[] = $newFilename;
                    $property->setImg($img);
                }
            }
//            dd($property);
            $entityManager->persist($property);
            $entityManager->flush();

            return $this->redirectToRoute('app_property_index');
        }

        return $this->render('property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
            'is_edit' => $property->getId() !== null,
        ]);
    }

    #[Route('/{id}', name: 'app_property_show', methods: ['GET'])]
    public function show(Property $property): Response
    {
        return $this->render('property/show.html.twig', [
            'property' => $property,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_property_edit', methods: ['GET', 'POST','DELETE'])]
    public function edit(Request $request, Property $property, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle uploaded images
            $images = $form->get('imageFiles')->getData(); // Use 'imageFiles' from the form


            if ($images) {
                foreach ($images as $image) {
                    /** @var UploadedFile $image */
                    $newFilename = uniqid() . '.' . $image->guessExtension();

                    // Move the file to the directory where images are stored
                    $image->move(
                        $this->getParameter('images_directory'), // Check this parameter
                        $newFilename
                    );

                    // Add the new filename to the img array
                    $img = $property->getImg();
                    $img[] = $newFilename;
                    $property->setImg($img);
                }
            }
            $entityManager->persist($property);
            $entityManager->flush();

            return $this->redirectToRoute('app_property_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
            'is_edit' => $property->getId() !== null,
        ]);
    }

    #[Route('/{id}', name: 'app_property_delete', methods: ['POST'])]
    public function delete(Request $request, Property $property, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->request->get('_token'))) {
            $entityManager->remove($property);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_property_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/property/{id}/delete-image/{image}', name: 'property_delete_image', methods: ['GET', 'POST','DELETE'])]
    public function deleteImage($id, $image, EntityManagerInterface $entityManager): Response
    {
        // Fetch the property using the repository
        $property = $entityManager->getRepository(Property::class)->find($id);
        if ($property) {
            $images = $property->getImg();

            // Remove the image from the array
            if (($key = array_search($image, $images)) !== false) {
                unset($images[$key]);

                // Delete the image file from the server
                $filePath = $this->getParameter('images_directory') . '/' . $image;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                // Update the property entity
                $property->setImg($images);
                $entityManager->persist($property);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('app_property_edit', ['id' => $id]);
    }
    #[Route('/api/properties', name: 'api_properties')]
    public function apiIndex(PropertyRepository $propertyRepository,AgenceRepository $agenceRepository): JsonResponse
    {
        $properties = $propertyRepository->findAll();

        $data = [];
        foreach ($properties as $property) {
            $agence = $agenceRepository->findById($property->getAgence()->getId());

            $data[] = [
                'id' => $property->getId(),
                'title' => $property->getTitle(),
                'description' => $property->getDescription(),
                'price' => $property->getPrice(),
                'promotion' => $property->getPromotion(), // Using the promotion field
                'surface' => $property->getSurface(),
                'rooms' => $property->getRooms(),
                'beds' => $property->getBeds(),
                'type' => $property->getType(),
                'propertyStatus' => $property->getPropertyStatus(),
                'prixPromo' => $property->getPrixPromo(), // Added prixPromo field
                'bath' => $property->getBath(),
                'sold' => $property->getSold(), // Assuming you have an isSold() method
                'city' => $property->getCity(),
                'neighborhood' => $property->getNeighborhood(),
                'agencyName' => $agence[0]->getName(), // Assuming Agence entity has a getName() method
                'agencyId' => $agence[0]->getId(), // Assuming Agence entity has an getId() method
                'agencyIcon' => $agence[0]->getImageFilename(), // Assuming Agence entity has an getId() method
                'images' => array_map(function($image) {
                    return 'http://127.0.0.1:8000/uploads/images/' . $image;
                }, $property->getImg()),
            ];
        }

        return new JsonResponse($data);
    }
    #[Route('/api/properties/{id}', name: 'api_properties_show', methods: ['GET'])]
    public function apiIndex1($id,Request $request, Property $property, EntityManagerInterface $entityManager,PropertyRepository $propertyRepository,AgenceRepository $agenceRepository): JsonResponse
    {
        $property = $propertyRepository->findById($id);
        $property= $property[0];
        $data = [];
            $agence = $agenceRepository->findById($property->getAgence()->getId());
            $data[] = [
                'id' => $property->getId(),
                'title' => $property->getTitle(),
                'description' => $property->getDescription(),
                'price' => $property->getPrice(),
                'promotion' => $property->getPromotion(), // Using the promotion field
                'surface' => $property->getSurface(),
                'rooms' => $property->getRooms(),
                'beds' => $property->getBeds(),
                'type' => $property->getType(),
                'propertyStatus' => $property->getPropertyStatus(),
                'prixPromo' => $property->getPrixPromo(), // Added prixPromo field
                'bath' => $property->getBath(),
                'sold' => $property->getSold(), // Assuming you have an isSold() method
                'city' => $property->getCity(),
                'neighborhood' => $property->getNeighborhood(),
                'agencyName' => $agence[0]->getName(), // Assuming Agence entity has a getName() method
                'agencyId' => $agence[0]->getId(), // Assuming Agence entity has an getId() method
                'agencyIcon' => $agence[0]->getImageFilename(), // Assuming Agence entity has an getId() method
                'images' => array_map(function($image) {
                    return 'http://127.0.0.1:8000/uploads/images/' . $image;
                }, $property->getImg()),
            ];


        return new JsonResponse($data);
    }

}
