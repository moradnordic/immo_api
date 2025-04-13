<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Create a new user',
)]
class CreateUserCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure(): void
{
    $this
        ->addArgument('email', InputArgument::REQUIRED, 'The email of the user')
        ->addArgument('password', InputArgument::REQUIRED, 'The plain password of the user')
        ->addArgument('name', InputArgument::OPTIONAL, 'Name of the user')
        ->addArgument('role', InputArgument::OPTIONAL, 'Role of the user (default ROLE_USER)');
}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $plainPassword = $input->getArgument('password');
        $name = $input->getArgument('name');
        $role = $input->getArgument('role') ?? 'ROLE_USER';

        $user = new User();
        $user->setEmail($email);
        $user->setName($name);
        $user->setRoles([$role]);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln("✅ User $email created successfully with role $role.");

        return Command::SUCCESS;
    }
}