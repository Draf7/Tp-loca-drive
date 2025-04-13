<?php

namespace App\Application;

use App\Repository\ReservationRepositoryInterface;
use App\Entity\Reservation;
use App\Exception\ReservationNotFoundException;

class AddInsuranceToReservationUseCase
{
    private ReservationRepositoryInterface $reservationRepo;

    public function __construct(ReservationRepositoryInterface $reservationRepo)
    {
        $this->reservationRepo = $reservationRepo;
    }

    public function execute(int $reservationId, bool $insurance): void
    {
        // Fetch the reservation
        $reservation = $this->reservationRepo->find($reservationId);

        if (!$reservation) {
            throw new ReservationNotFoundException('Reservation not found.');
        }

        // If insurance is already added, throw exception
        if ($reservation->getInsurance() && $insurance) {
            throw new \Exception('Insurance already added.');
        }

        // Update the reservation with the insurance
        $reservation->setInsurance($insurance);

        // Save the updated reservation
        $this->reservationRepo->save($reservation);
    }
}
