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
// 1. Import the ORM EntityManager Interface
use Doctrine\ORM\EntityManagerInterface;
//Entities
use App\Entity\Region;

// the name of the command is what users type after "php bin/console"
#[AsCommand(
            name: 'app:create-region',
            description: 'Creates regions.',
            hidden: false,
            aliases: ['app:add-region']
    )]
class CreateRegionCommand extends Command {

    const DEFAULT_REGIONS = [
        ["name" => "Ariana",
            "code" => "TN-12"],
        ["name" => "Béja",
            "code" => "TN-31"],
        ["name" => "Ben Arous",
            "code" => "TN-13"],
        ["name" => "Bizerte",
            "code" => "TN-23"],
        ["name" => "Gabès",
            "code" => "TN-81"],
        ["name" => "Gafsa",
            "code" => "TN-71"],
        ["name" => "Jendouba",
            "code" => "TN-32"],
        ["name" => "Kairouan",
            "code" => "TN-41"],
        ["name" => "Kasserine",
            "code" => "TN-42"],
        ["name" => "Kébili",
            "code" => "TN-73"],
        ["name" => "Le Kef",
            "code" => "TN-33"],
        ["name" => "Mahdia",
            "code" => "TN-53"],
        ["name" => "La Manouba",
            "code" => "TN-14"],
        ["name" => "Médenine",
            "code" => "TN-82"],
        ["name" => "Monastir",
            "code" => "TN-52"],
        ["name" => "Nabeul",
            "code" => "TN-21"],
        ["name" => "Sfax",
            "code" => "TN-61"],
        ["name" => "Sidi Bouzid",
            "code" => "TN-43"],
        ["name" => "Siliana",
            "code" => "TN-34"],
        ["name" => "Sousse",
            "code" => "TN-51"],
        ["name" => "Tataouine",
            "code" => "TN-83"],
        ["name" => "Tozeur",
            "code" => "TN-72"],
        ["name" => "Tunis",
            "code" => "TN-11"],
        ["name" => "Zaghouan",
            "code" => "TN-22"]
    ];

    public function __construct(
            private readonly EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function configure(): void {
        $this
                // the command description shown when running "php bin/console list"
                ->setDescription('Creates a new region.')
                // the command help shown when running the command with the "--help" option
                ->setHelp('This command allows you to create a region...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln('Region Creator ============>');
        foreach (self::DEFAULT_REGIONS as $regionValue) {
           
            $regionExist = $this->em->getRepository(Region::class)->findByCode($regionValue["code"]);
            if (empty($regionExist)) {
                $region = new Region();
                $region->setName($regionValue["name"]);
                $region->setCode($regionValue["code"]);
                $this->em->persist($region);
                $this->em->flush();
            }
        }

        $output->writeln('Creater regions with SUCCESS.' . "\n");

        return Command::SUCCESS;
    }
}
