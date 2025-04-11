<?php

namespace App\Application;

use App\Entity\Vehicle;
use App\Repository\VehicleRepository;

class AddVehicle
{
    private $vehicleRepo;

    public function __construct(VehicleRepository $vehicleRepo)
    {
        $this->vehicleRepo = $vehicleRepo;
    }

    public function execute(string $brand, string $model, float $dailyPrice): Vehicle
    {
        // Créer un nouvel objet Vehicle
        $vehicle = new Vehicle();
        $vehicle->setBrand($brand)
                ->setModel($model)
                ->setDailyPrice($dailyPrice);

        // Sauvegarder le véhicule dans la base de données
        $this->vehicleRepo->save($vehicle);

        return $vehicle;
    }
}
