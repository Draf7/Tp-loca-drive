<?php

namespace App\Controller;

use App\Application\RegisterClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends AbstractController
{
    public function register(RegisterClient $registerClient, Request $request): Response
    {
        $client = $registerClient->execute(
            $request->request->get('email'),
            $request->request->get('password'),
            $request->request->get('firstName'),
            $request->request->get('lastName'),
        
            new \DateTime($request->request->get('licenseDate'))
        );

        return $this->json(['id' => $client->getId()], Response::HTTP_CREATED);
    }
}