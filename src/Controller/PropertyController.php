<?php

namespace App\Controller;

use App\Entity\Property;
use App\Form\PropertyStep1Type;
use App\Form\PropertyStep2Type;
use App\Form\PropertyStep3Type;
use App\Form\PropertyType;
use App\Repository\AgenceRepository;
use App\Repository\PropertyRepository;
use App\Service\PropertyFormSessionManager;
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
    public function __construct(
        private  PropertyFormSessionManager $sessionManager,
        private  EntityManagerInterface     $entityManager,
        private  Security                   $security
    ) {}
    #[Route(name: 'app_property_index', methods: ['GET'])]
    public function index(PropertyRepository $propertyRepository, Security $security): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
            $properties = $propertyRepository->findAll(); // Admin gets all properties
        } else {
            $user = $security->getUser();

            $properties = $propertyRepository->findBy(['agence' => $user->getAgence()]);

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
//        $classMetadata = $entityManager->getClassMetadata(Property::class);
//        $fields = $classMetadata->getFieldNames();
//
//        dd($fields); // Dump all field names
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
            $property->setAgence($user->getAgence());

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

            return $this->redirectToRoute('app_property_index');
        }

        return $this->render('property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
            'is_edit' => $property->getId() !== null,
        ]);
    }

    #[Route('/new/step1', name: 'app_property_new_step1', methods: ['GET', 'POST'])]
    public function newStep1(Request $request): Response
    {
        $user = $this->security->getUser();
        $agence = $user->getAgence();

        // Create form with existing data if available
        $existingData = $this->sessionManager->getStep1Data();

        $form = $this->createForm(PropertyStep1Type::class, $existingData, [
            'agence' => $agence,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save step 1 data to session
            $this->sessionManager->saveStep1Data($form->getData());

            // Get property type for next step
            $propertyType = $form->get('type')->getData();

            return $this->redirectToRoute('app_property_new_step2', [
                'type' => $propertyType
            ]);
        }

        return $this->render('property/new_step1.html.twig', [
            'form' => $form->createView(),
            'step' => 1,
            'totalSteps' => 3,
        ]);
    }

    #[Route('/new/step2/{type}', name: 'app_property_new_step2', methods: ['GET', 'POST'])]
    public function newStep2(string $type, Request $request): Response
    {
        // Check if step 1 is completed
        if (!$this->sessionManager->hasStep1Data()) {
            $this->addFlash('error', 'Veuillez d\'abord compléter l\'étape 1.');
            return $this->redirectToRoute('app_property_new_step1');
        }

        // Validate property type
        $validTypes = ['appartement', 'maison', 'villa', 'bureau', 'magasin', 'ferme'];
        if (!in_array($type, $validTypes)) {
            $this->addFlash('error', 'Type de propriété invalide.');
            return $this->redirectToRoute('app_property_new_step1');
        }

        // Create form with existing data if available
        $existingData = $this->sessionManager->getStep2Data();

        $form = $this->createForm(PropertyStep2Type::class, $existingData, [
            'property_type' => $type,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save step 2 data to session
            $this->sessionManager->saveStep2Data($form->getData());

            return $this->redirectToRoute('app_property_new_step3');
        }

        return $this->render('property/new_step2.html.twig', [
            'form' => $form->createView(),
            'propertyType' => $type,
            'step' => 2,
            'totalSteps' => 3,
        ]);
    }

    #[Route('/new/step3', name: 'app_property_new_step3', methods: ['GET', 'POST'])]
    public function newStep3(Request $request): Response
    {
        // Check if previous steps are completed
        if (!$this->sessionManager->hasStep1Data() || !$this->sessionManager->hasStep2Data()) {
            $this->addFlash('error', 'Veuillez compléter les étapes précédentes.');
            return $this->redirectToRoute('app_property_new_step1');
        }

        $form = $this->createForm(PropertyStep3Type::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Create new Property entity
                $property = new Property();
                $user = $this->security->getUser();

                // Get all data from session
                $step1Data = $this->sessionManager->getStep1Data();
                $step2Data = $this->sessionManager->getStep2Data();

                // Set step 1 data
                $property->setTitle($step1Data['title'] ?? '');
                $property->setDescription($step1Data['description'] ?? '');
                $property->setPrice($step1Data['price'] ?? 0);
                $property->setType($step1Data['type'] ?? '');
                $property->setPropertyStatus($step1Data['propertyStatus'] ?? '');
                $property->setAgeBien($step1Data['ageBien'] ?? null);
                $property->setPromotion($step1Data['promotion'] ?? null);
                $property->setPrixPromo($step1Data['prixPromo'] ?? null);
                $property->setTypeUsage($step1Data['type_usage'] ?? '');
                $property->setSurface($step1Data['surface'] ?? 0);
                $property->setCity($step1Data['city'] ?? '');
                $property->setNeighborhood($step1Data['neighborhood'] ?? '');
                $property->setSold($step1Data['sold'] ?? false);

                // Set step 2 data dynamically
                foreach ($step2Data as $field => $value) {
                    $setter = 'set' . ucfirst($field);
                    if (method_exists($property, $setter)) {
                        $property->$setter($value);
                    }
                }

                // Handle uploaded images
                $images = $form->get('imageFiles')->getData();
                if ($images) {
                    $imageFilenames = [];
                    foreach ($images as $image) {
                        /** @var UploadedFile $image */
                        $newFilename = uniqid() . '.' . $image->guessExtension();

                        // Move the file to the directory where images are stored
                        $image->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );

                        $imageFilenames[] = $newFilename;
                    }
                    $property->setImg($imageFilenames);
                }

                // Set default values
                $property->setCreatedAt(new DateTimeImmutable());
                $property->setCountry('maroc');
                $property->setAgence($user->getAgence());
                // Persist entity
                $this->entityManager->persist($property);
                $this->entityManager->flush();

                // Clear session data
                $this->sessionManager->clearData();

                $this->addFlash('success', 'La propriété a été créée avec succès!');
                return $this->redirectToRoute('app_property_index');

            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la création de la propriété.');

                // Log the error if you have a logger service
                // $this->logger->error('Property creation failed: ' . $e->getMessage());
            }
        }

        return $this->render('property/new_step3.html.twig', [
            'form' => $form->createView(),
            'step' => 3,
            'totalSteps' => 3,
            'step1Data' => $this->sessionManager->getStep1Data(),
            'step2Data' => $this->sessionManager->getStep2Data(),
        ]);
    }

    #[Route('/new/back-to-step1', name: 'app_property_back_to_step1')]
    public function backToStep1(): Response
    {
        return $this->redirectToRoute('app_property_new_step1');
    }

    #[Route('/new/back-to-step2', name: 'app_property_back_to_step2')]
    public function backToStep2(): Response
    {
        $propertyType = $this->sessionManager->getPropertyType();

        if (!$propertyType) {
            return $this->redirectToRoute('app_property_new_step1');
        }

        return $this->redirectToRoute('app_property_new_step2', [
            'type' => $propertyType
        ]);
    }

    #[Route('/new/cancel', name: 'app_property_new_cancel')]
    public function cancelPropertyCreation(): Response
    {
        $this->sessionManager->clearData();
        $this->addFlash('info', 'La création de propriété a été annulée.');

        return $this->redirectToRoute('app_property_index');
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
            $data = [
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
    #[Route('/api/Search ', name: 'api_properties_Search', methods: ['GET'])]
    public function apiIndex2(
        PropertyRepository $propertyRepository,
        AgenceRepository $agenceRepository,
        Request $request
    ): JsonResponse {
        // Get search parameters from query string
        $searchParams = [
            'title' => $request->query->get('title'),
            'minPrice' => $request->query->get('minPrice'),
            'maxPrice' => $request->query->get('maxPrice'),
            'type' => $request->query->get('type'),
            'city' => $request->query->get('city'),
            'neighborhood' => $request->query->get('neighborhood'),
            'minRooms' => $request->query->get('minRooms'),
            'minBeds' => $request->query->get('minBeds'),
            'minBath' => $request->query->get('minBath'),
            'minSurface' => $request->query->get('minSurface'),
            'propertyStatus' => $request->query->get('propertyStatus'),
            'agence' => $request->query->get('agence'),
            'promotion' => $request->query->get('promotion'),
        ];

        // Find properties based on search criteria
        $properties = $propertyRepository->findBySearchCriteria($searchParams);

        $data = [];
        foreach ($properties as $property) {
            $agence = $agenceRepository->findById($property->getAgence()->getId());

            $data[] = [
                'id' => $property->getId(),
                'title' => $property->getTitle(),
                'description' => $property->getDescription(),
                'price' => $property->getPrice(),
                'promotion' => $property->getPromotion(),
                'surface' => $property->getSurface(),
                'rooms' => $property->getRooms(),
                'beds' => $property->getBeds(),
                'type' => $property->getType(),
                'propertyStatus' => $property->getPropertyStatus(),
                'prixPromo' => $property->getPrixPromo(),
                'bath' => $property->getBath(),
                'sold' => $property->getSold(),
                'city' => $property->getCity(),
                'neighborhood' => $property->getNeighborhood(),
                'agencyName' => $agence[0]->getName(),
                'agencyId' => $agence[0]->getId(),
                'agencyIcon' => $agence[0]->getImageFilename(),
                'images' => array_map(function($image) {
                    return 'http://127.0.0.1:8000/uploads/images/' . $image;
                }, $property->getImg()),
            ];
        }

        return new JsonResponse($data);
    }
}
