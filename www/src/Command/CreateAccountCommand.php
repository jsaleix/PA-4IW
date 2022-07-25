<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\User;

#[AsCommand(
    name: 'createAccount',
    description: 'Create a user account',
)]
class CreateAccountCommand extends Command
{

    public function __construct(
                                private UserPasswordHasherInterface $userPasswordHasher,
                                private EntityManagerInterface $manager
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('mail', InputArgument::OPTIONAL, 'User email')
            ->addArgument('pwd', InputArgument::OPTIONAL, 'User password')
            ->addArgument('role', InputArgument::OPTIONAL, 'Role (optional, default: USER)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $mail = $input->getArgument('mail');
        $pwd = $input->getArgument('pwd');
        $role = $input->getArgument('role');

        try{
        
            if ( empty($mail)) {
                throw new \Exception("Missing mail");
            }
    
            if ( empty($pwd)) {
                throw new \Exception("Missing password");
            }
    
            if ( !empty($role) && !(in_array($role, USER::ROLES)) ) {
                throw new \Exception("Unknown role: $role");
            }
            
            $created = $this->createUser([
                'email' => $mail,
                'password' => $pwd,
                'role' => $role,
            ]);

            if(!$created){
                throw new \Exception('An error occurred, could not create this user');
            }

            $io->success("User created! You can now log with email: $mail and password: $pwd");
            return Command::SUCCESS;

        }catch(\Exception $e){
            $io->error($e->getMessage());
            return Command::FAILURE;       
        }

    }

    private function createUser(array $UserValues): bool
    {
        [ "email" => $email, "password" => $password, "role" => $role ] = $UserValues;

        $user = new User();
        $user->setEmail($email);
        $user->setIsVerified(true);
        if($role){
            $user->setRoles([$role]);
        }
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));

        $this->manager->persist($user);
        $this->manager->flush();
        return true;
    }
}
