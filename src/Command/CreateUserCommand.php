<?php

namespace App\Command;

/**
 * Description of CreateRegionCommand
 *
 * @author Lamine Mansouri <mansourilamine19@gmail.com>
 */
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
// 1. Import the ORM EntityManager Interface
use Doctrine\ORM\EntityManagerInterface;
use App\Enum\RoleUserEnum;
//Entities
use App\Entity\User;

// the name of the command is what users type after "php bin/console"
#[AsCommand(
            name: 'app:create-user',
            description: 'Creates a new user.',
            hidden: false,
            aliases: ['app:add-user']
    )]
class CreateUserCommand extends Command {

    public const EMAIL = 'wided@gmail.com';

    public function __construct(
            private readonly EntityManagerInterface $em,
            private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
        parent::__construct();
    }

    protected function configure(): void {
        $this
                // the command description shown when running "php bin/console list"
                ->setDescription('Creates a new user.')
                // the command help shown when running the command with the "--help" option
                ->setHelp('This command allows you to create a user...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln('User Creator ============>');

        $user = $this->em->getRepository(User::class)->findOneByEmail(self::EMAIL);
        if (empty($user)) {
            $user = new User();
            $user->setFullName("Wided Administrator RH")
                    ->setEmail(self::EMAIL)
                    ->setPassword($this->userPasswordHasher->hashPassword($user, self::EMAIL))
                    ->setRoles(array_column(RoleUserEnum::cases(), 'name'))
                    ->setAdresse("This is a complement adresse")
                    ->setTel("0772342354")
                    ->setVerified(true);

            $this->em->persist($user);
            $this->em->flush();
            // outputs a message followed by a "\n"
            $output->writeln('Creater user SUCCESS.' . "\n");
        }

        return Command::SUCCESS;
    }
}
