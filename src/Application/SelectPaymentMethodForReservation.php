<?php

namespace App\Application;

use App\Entity\Reservation;
use App\Repository\ReservationRepositoryInterface;
use App\Exceptions\ReservationNotFoundException;

class SelectPaymentMethodForReservation
{
    private ReservationRepositoryInterface $reservationRepo;

    public function __construct(ReservationRepositoryInterface $reservationRepo)
    {
        $this->reservationRepo = $reservationRepo;
    }

    public function execute(int $reservationId, string $paymentMethod): Reservation
    {
        // Trouver la réservation à partir de son ID
        $reservation = $this->reservationRepo->find($reservationId);

        if (!$reservation) {
            throw new ReservationNotFoundException('Reservation not found.');
        }

        // Vérification de l'état de la commande
        if ($reservation->getStatus() !== 'CART') {
            throw new \Exception('Payment method can only be selected for reservations in CART status.');
        }

        // Vérification du mode de paiement
        $validPaymentMethods = ['CB', 'PayPal']; // Vous pouvez ajouter d'autres moyens de paiement ici
        if (!in_array($paymentMethod, $validPaymentMethods)) {
            throw new \Exception('Invalid payment method.');
        }

        // Définir le mode de paiement choisi
        $reservation->setPaymentMethod($paymentMethod);

        // Sauvegarder la réservation mise à jour
        $this->reservationRepo->save($reservation);

        return $reservation;
    }
}
