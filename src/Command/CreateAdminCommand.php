<?php

namespace App\Command;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create-admin';
    protected static $defaultDescription = 'Crée un compte administrateur par défaut';

    private $em;
    private $passwordEncoder;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        parent::__construct();
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function configure()
    {
        $this
            ->setDescription('Crée un administrateur avec email admin@locadrive.com')
            ->setHelp('Cette commande crée un admin avec ROLE_ADMIN et mot de passe temporaire');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $admin = new Admin();
        $admin->setEmail('admin@locadrive.com');
        $admin->setRoles(['ROLE_ADMIN']);
        
        $password = 'admin123';
        $admin->setPassword($this->passwordEncoder->encodePassword($admin, $password));

        $this->em->persist($admin);
        $this->em->flush();

        $io->success('Administrateur créé avec succès !');
        $io->warning([
            'Identifiants temporaires :',
            'Email: admin@locadrive.com',
            'Mot de passe: admin123',
            'Changez ce mot de passe immédiatement après la première connexion !'
        ]);

        return 0;
    }
}