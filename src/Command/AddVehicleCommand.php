<?php

namespace App\Command;

use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddVehicleCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected static $defaultName = 'app:add-vehicle';

    protected function configure()
    {
        $this
            ->setDescription('Ajoute un véhicule à la base de données')
            ->setHelp('Cette commande permet d\'ajouter un véhicule à la base de données...')
            ->addArgument('brand', InputArgument::REQUIRED, 'La marque du véhicule')
            ->addArgument('model', InputArgument::REQUIRED, 'Le modèle du véhicule')
            ->addArgument('dailyPrice', InputArgument::REQUIRED, 'Le prix quotidien du véhicule');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $brand = $input->getArgument('brand');
        $model = $input->getArgument('model');
        $dailyPrice = $input->getArgument('dailyPrice');

        // Crée un nouveau véhicule avec les données fournies
        $vehicle = new Vehicle();
        $vehicle->setBrand($brand);
        $vehicle->setModel($model);
        $vehicle->setDailyPrice((float)$dailyPrice);

        // Sauvegarde du véhicule dans la base de données
        $this->entityManager->persist($vehicle);
        $this->entityManager->flush();

        $output->writeln('Véhicule ajouté avec succès !');

        return Command::SUCCESS;
    }
}
