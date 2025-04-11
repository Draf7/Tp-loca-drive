<?php

namespace App\Application;

use App\Entity\Client;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterClient
{
    private $userRepo;
    private $passwordHasher;

    public function __construct(UserRepository $userRepo, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepo = $userRepo;
        $this->passwordHasher = $passwordHasher;
    }

    public function execute(
        string $email,
        string $plainPassword,
        string $firstName,
        string $lastName,
        \DateTimeInterface $licenseDate
    ): Client {
        $client = new Client();
        $client->setEmail($email);
        $client->setPassword($this->passwordHasher->hashPassword($client, $plainPassword));
        $client->setFirstName($firstName);
        $client->setLastName($lastName);
        $client->setLicenseObtainedAt($licenseDate);
        $client->setRoles(['ROLE_CLIENT']);

        $this->userRepo->save($client);
        return $client;
    }
}
