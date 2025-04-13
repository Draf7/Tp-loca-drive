<?php

namespace App\Application;

use App\Entity\Reservation;
use App\Repository\ReservationRepositoryInterface;
use App\Exceptions\ReservationNotFoundException;

class PayForReservation
{
    private ReservationRepositoryInterface $reservationRepo;

    public function __construct(ReservationRepositoryInterface $reservationRepo)
    {
        $this->reservationRepo = $reservationRepo;
    }

    public function execute(int $reservationId, float $paymentAmount): Reservation
    {
        // Trouver la réservation à partir de son ID
        $reservation = $this->reservationRepo->find($reservationId);

        if (!$reservation) {
            throw new ReservationNotFoundException('Reservation not found.');
        }

        // Vérification que la réservation est en statut "CART"
        if ($reservation->getStatus() !== 'CART') {
            throw new \Exception('Payment can only be processed for reservations in CART status.');
        }

        // Vérification que le montant payé correspond au total de la réservation
        if ($paymentAmount < $reservation->getTotalPrice()) {
            throw new \Exception('Insufficient payment amount.');
        }

        // Mettre à jour le statut de la réservation à "PAID"
        $reservation->setStatus('PAID');
        
        // Sauvegarder la réservation mise à jour
        $this->reservationRepo->save($reservation);

        return $reservation;
    }
}
