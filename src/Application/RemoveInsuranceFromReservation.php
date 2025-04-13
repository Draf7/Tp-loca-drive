<?php

namespace App\Application;

use App\Entity\Reservation;
use App\Repository\ReservationRepositoryInterface;
use App\Exceptions\ReservationNotFoundException;

class RemoveInsuranceFromReservation
{
    private ReservationRepositoryInterface $reservationRepo;

    public function __construct(ReservationRepositoryInterface $reservationRepo)
    {
        $this->reservationRepo = $reservationRepo;
    }

    public function execute(int $reservationId): Reservation
    {
        // Trouver la réservation à partir de son ID
        $reservation = $this->reservationRepo->find($reservationId);

        if (!$reservation) {
            throw new ReservationNotFoundException('Reservation not found.');
        }

        // Vérification de l'état de la commande
        if ($reservation->getStatus() !== 'CART') {
            throw new \Exception('Insurance can only be removed from reservations in CART status.');
        }

        // Retirer l'assurance et ajuster le prix total
        if ($reservation->getInsurance()) {
            $reservation->setInsurance(false);
            $reservation->setTotalPrice($reservation->getTotalPrice() - 20); // Retirer 20€ pour l'assurance
        }

        // Sauvegarder la réservation mise à jour
        $this->reservationRepo->save($reservation);

        return $reservation;
    }
}
