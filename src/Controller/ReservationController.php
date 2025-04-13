<?php

namespace App\Controller;

use App\Application\PayForReservation;
use App\Repository\ReservationRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends AbstractController
{
    /**
     * @Route("/api/reservations/{id}/pay", name="pay_for_reservation", methods={"POST"})
     */
    public function payForReservation(
        int $id,
        Request $request,
        PayForReservation $payForReservation
    ): JsonResponse {
        try {
            // Récupérer le montant payé depuis la requête
            $paymentAmount = (float) $request->get('paymentAmount');
            
            if (!$paymentAmount) {
                return $this->json([
                    'error' => 'Payment amount is required.',
                ], Response::HTTP_BAD_REQUEST);
            }

            // Appel du use case pour effectuer le paiement
            $reservation = $payForReservation->execute($id, $paymentAmount);

            // Retourner la réservation mise à jour
            return $this->json([
                'id' => $reservation->getId(),
                'status' => $reservation->getStatus(),
                'totalPrice' => $reservation->getTotalPrice(),
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
