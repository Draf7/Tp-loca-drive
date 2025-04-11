<?php

namespace App\Application;

use App\Repository\VehicleRepository;
use App\Exception\VehicleNotFoundException;

class DeleteVehicle
{
    private $vehicleRepository;

    public function __construct(VehicleRepository $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    public function execute(int $vehicleId): void
    {
        $vehicle = $this->vehicleRepository->find($vehicleId);

        if (!$vehicle) {
            throw new VehicleNotFoundException(sprintf('Le véhicule avec l\'ID %d n\'existe pas', $vehicleId));
        }

        $this->vehicleRepository->remove($vehicle, true);
    }
}
