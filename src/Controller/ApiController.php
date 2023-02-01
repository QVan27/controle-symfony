<?php

namespace App\Controller;

use Faker;
use App\Entity\Media;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    #[Route('/', name: 'app_api')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $medias = $doctrine->getRepository(Media::class)->findAll();

        $medias = array_map(function ($media) {
            return [
                'id' => $media->getId(),
                'nom' => $media->getNom(),
                'synopsis' => $media->getSynopsis(),
                'type' => $media->getType(),
                'date' => $media->getDate()
            ];
        }, $medias);


        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
            'medias' => $medias,
        ]);
    }

    #[Route('/create', name: 'app_api_create')]
    public function create(ManagerRegistry $doctrine): JsonResponse
    {
        $faker = Faker\Factory::create('fr_FR');
        $media = new Media();

        $media->setNom($faker->name);
        $media->setSynopsis($faker->text);
        $media->setType($faker->randomElement(['film', 'série']));
        $media->setDate($faker->dateTime);

        $doctrine->getManager()->persist($media);
        $doctrine->getManager()->flush();

        $response = new JsonResponse();
        $response->setData([
            'status' => 201,
            'message' => 'Resource created',
            'data' => [
                'id' => $media->getId(),
                'nom' => $media->getNom(),
                'synopsis' => $media->getSynopsis(),
                'type' => $media->getType(),
                'date' => $media->getDate()
            ]
        ]);
        return $response;
    }

    #[Route('/getall', name: 'app_api_read')]
    public function read(ManagerRegistry $doctrine): JsonResponse
    {
        try {
            $medias = $doctrine->getRepository(Media::class)->findAll();

            $medias = array_map(function ($media) {
                return [
                    'id' => $media->getId(),
                    'nom' => $media->getNom(),
                    'synopsis' => $media->getSynopsis(),
                    'type' => $media->getType(),
                    'date' => $media->getDate()
                ];
            }, $medias);

            $response = new JsonResponse();
            $response->setData([
                'status' => 200,
                'message' => 'List of medias',
                'data' => $medias
            ]);

            return $response;
        } catch (\Throwable $th) {
            switch (get_class($th)) {
                case 'Symfony\Component\HttpKernel\Exception\NotFoundHttpException':
                    $response = new JsonResponse();
                    $response->setData([
                        'status' => 404,
                        'message' => 'Resource not found'
                    ]);
                    return $response;
                    break;
                default:
                    $response = new JsonResponse();
                    $response->setData([
                        'status' => 500,
                        'message' => 'Internal server error'
                    ]);
                    return $response;
                    break;
            }
        }
    }

    #[Route('/get/{id}', name: 'app_api_read_id')]
    public function readId(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        try {
            $media = $doctrine->getRepository(Media::class)->find($id);

            $media = [
                'id' => $media->getId(),
                'nom' => $media->getNom(),
                'synopsis' => $media->getSynopsis(),
                'type' => $media->getType(),
                'date' => $media->getDate()
            ];

            $response = new JsonResponse();
            $response->setData([
                'status' => 200,
                'message' => 'Media by id',
                'data' => $media
            ]);
            return $response;
        } catch (\Throwable $th) {
            switch (get_class($th)) {
                case 'Symfony\Component\HttpKernel\Exception\NotFoundHttpException':
                    $response = new JsonResponse();
                    $response->setData([
                        'status' => 404,
                        'message' => 'Resource not found'
                    ]);
                    return $response;
                    break;
                default:
                    $response = new JsonResponse();
                    $response->setData([
                        'status' => 500,
                        'message' => 'Internal server error'
                    ]);
                    return $response;
                    break;
            }
        }
    }
}
