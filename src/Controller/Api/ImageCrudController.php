<?php

namespace App\Controller\Api;

use App\Entity\Image;
use App\Repository\ServiceRepository;
use App\Repository\HabitatRepository;
use App\Repository\AnimalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageCrudController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/{entityType}/{entityId}/images', name: 'api_entity_images_list', methods: ['GET'])]
    public function index(string $entityType, int $entityId, ServiceRepository $serviceRepository, HabitatRepository $habitatRepository, AnimalRepository $animalRepository): Response
    {
        // Récupérer l'entité spécifiée (Service, Habitat ou Animal)
        switch ($entityType) {
            case 'service':
                $entity = $serviceRepository->find($entityId);
                break;
            case 'habitat':
                $entity = $habitatRepository->find($entityId);
                break;
            case 'animal':
                $entity = $animalRepository->find($entityId);
                break;
            default:
                $entity = null;
        }

        if (!$entity) {
            return $this->json(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        // Récupérer les images associées à l'entité
        $images = $entity->getImages();

        // Retourner une réponse JSON contenant les images
        return $this->json($images, Response::HTTP_OK, [], ['groups' => 'image']);
    }

    #[Route('/api/{entityType}/{entityId}/images/upload', name: 'api_entity_image_upload', methods: ['POST'])]
    public function upload(string $entityType, int $entityId, ServiceRepository $serviceRepository, HabitatRepository $habitatRepository, AnimalRepository $animalRepository): Response
    {
        // Gérer le téléchargement de l'image depuis la requête
        // Enregistrer l'image dans le système de fichiers ou dans un système de stockage en nuage

        // Créer une nouvelle instance d'image
        $image = new Image();

        // Récupérer l'entité spécifiée (Service, Habitat ou Animal)
        switch ($entityType) {
            case 'service':
                $entity = $serviceRepository->find($entityId);
                if (!$entity) {
                    return $this->json(['error' => 'Service not found'], Response::HTTP_NOT_FOUND);
                }
                $image->setService($entity);
                break;
            case 'habitat':
                $entity = $habitatRepository->find($entityId);
                if (!$entity) {
                    return $this->json(['error' => 'Habitat not found'], Response::HTTP_NOT_FOUND);
                }
                $image->setHabitat($entity);
                break;
            case 'animal':
                $entity = $animalRepository->find($entityId);
                if (!$entity) {
                    return $this->json(['error' => 'Animal not found'], Response::HTTP_NOT_FOUND);
                }
                $image->setAnimal($entity);
                break;
            default:
                return $this->json(['error' => 'Invalid entity type'], Response::HTTP_BAD_REQUEST);
        }

        // Enregistrer l'image dans la base de données
        $entityManager = $this->entityManager;
        $entityManager->persist($image);
        $entityManager->flush();

        // Retourner une réponse JSON contenant les détails de l'image
        return $this->json($image, Response::HTTP_CREATED, [], ['groups' => 'image']);
    }

    #[Route('/api/{entityType}/{entityId}/images/{imageId}', name: 'api_entity_image_delete', methods: ['DELETE'])]
    public function delete(string $entityType, int $entityId, int $imageId, ServiceRepository $serviceRepository, HabitatRepository $habitatRepository, AnimalRepository $animalRepository): Response
    {
        // Récupérer l'entité spécifiée (Service, Habitat ou Animal)
        switch ($entityType) {
            case 'service':
                $entity = $serviceRepository->find($entityId);
                break;
            case 'habitat':
                $entity = $habitatRepository->find($entityId);
                break;
            case 'animal':
                $entity = $animalRepository->find($entityId);
                break;
            default:
                $entity = null;
        }

        if (!$entity) {
            return $this->json(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        // Récupérer l'image spécifiée
        $image = $this->entityManager->getRepository(Image::class)->find($imageId);

        if (!$image) {
            return $this->json(['error' => 'Image not found'], Response::HTTP_NOT_FOUND);
        }

        // Vérifier si l'image appartient bien à l'entité spécifiée
        if ($image->getEntity() !== $entity) {
            return $this->json(['error' => 'Image not associated with the entity'], Response::HTTP_BAD_REQUEST);
        }

        // Supprimer l'image de la base de données
        $entityManager = $this->entityManager;
        $entityManager->remove($image);
        $entityManager->flush();

        // Retourner une réponse JSON indiquant que l'image a été supprimée avec succès
        return $this->json(['message' => 'Image deleted successfully'], Response::HTTP_OK);
    }
}
