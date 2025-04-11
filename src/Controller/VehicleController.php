<?php

namespace App\Controller;

use App\Application\AddVehicle;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VehicleController extends AbstractController
{
    /**
     * API Endpoint - Create vehicle (POST)
     * @Route("/api/vehicles", name="vehicle_create", methods={"POST"})
     */
    public function create(AddVehicle $addVehicle, Request $request): JsonResponse
    {
        try {
            // Récupérer les données envoyées dans le corps de la requête
            $data = json_decode($request->getContent(), true);
            
            // Vérification des données envoyées
            if (!isset($data['brand'], $data['model'], $data['dailyPrice'])) {
                return $this->json([
                    'error' => 'Les champs "brand", "model", et "dailyPrice" sont requis.',
                ], Response::HTTP_BAD_REQUEST);
            }

            // Assurez-vous que les données sont bien formatées
            $brand = (string) $data['brand'];
            $model = (string) $data['model'];
            $dailyPrice = (float) $data['dailyPrice'];

            // Créer un véhicule via le use case
            $vehicle = $addVehicle->execute($brand, $model, $dailyPrice);

            // Retourner une réponse JSON avec les données du véhicule créé
            return $this->json([
                'id' => $vehicle->getId(),
                'brand' => $vehicle->getBrand(),
                'model' => $vehicle->getModel(),
                'dailyPrice' => $vehicle->getDailyPrice()
            ], Response::HTTP_CREATED);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'error' => $e->getMessage(),
                'code' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
    }

   /**
 * API Endpoint - Delete vehicle (DELETE)
 * @Route("/api/vehicles/{id}", name="vehicle_delete", methods={"DELETE"})
 */
public function delete(
    int $id,
    DeleteVehicle $deleteVehicle
): JsonResponse {
    try {
        $deleteVehicle->execute($id);

        return $this->json([
            'message' => 'Véhicule supprimé avec succès',
            'code' => Response::HTTP_OK
        ]);
    } catch (VehicleNotFoundException $e) {
        return $this->json([
            'error' => $e->getMessage(),
            'code' => Response::HTTP_NOT_FOUND
        ], Response::HTTP_NOT_FOUND);
    }
}

    /**
     * API Endpoint - List vehicles (GET)
     * @Route("/api/vehicles", name="vehicle_list", methods={"GET"})
     */
    public function list(VehicleRepository $repository): JsonResponse
    {
        // Récupérer la liste des véhicules
        $vehicles = $repository->findAllSorted();

        // Retourner les véhicules en réponse
        return $this->json(array_map(function ($vehicle) {
            return [
                'id' => $vehicle->getId(),
                'brand' => $vehicle->getBrand(),
                'model' => $vehicle->getModel(),
                'dailyPrice' => $vehicle->getDailyPrice(),
                '_links' => [
                    'self' => '/api/vehicles/' . $vehicle->getId()
                ]
            ];
        }, $vehicles));
    }

    /**
     * Web Interface - Display list of vehicles (GET)
     * @Route("/vehicles", name="vehicle_ui", methods={"GET"})
     */
    public function index(VehicleRepository $repository): Response
    {
        // Récupérer la liste des véhicules
        $vehicles = $repository->findAllSorted();

        // Rendre la vue avec la liste des véhicules
        return $this->render('vehicle/index.html.twig', [
            'vehicles' => $vehicles
        ]);
    }

    /**
     * Route d'accueil - Redirection vers /vehicles
     * @Route("/", name="home")
     */
    public function home(): RedirectResponse
    {
        return $this->redirectToRoute('vehicle_ui');
    }

}
