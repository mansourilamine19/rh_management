<?php

namespace App\Command;

/**
 * Description of CalculateRevenueCommand
 *
 * @author Lamine Mansouri <mansourilamine19@gmail.com>
 */
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\SecurityBundle\Security;
// 1. Import the ORM EntityManager Interface
use Doctrine\ORM\EntityManagerInterface;
//Entities
use App\Entity\User;
use App\Entity\PickUp;
use App\Entity\Runsheet;
use App\Entity\Recipe;
use App\Enum\PickUpStatus;
use App\Enum\RunsheetStatusEnum;

// the name of the command is what users type after "php bin/console"
#[AsCommand(
            name: 'app:calculate-revenue',
            description: 'Calculate Revenue.',
            hidden: false,
            aliases: ['app:calculate-revenue']
    )]
class CalculateRevenueCommand extends Command {

    public function __construct(
            private readonly EntityManagerInterface $em,
            private Security $security,
    ) {
        parent::__construct();
    }

    protected function configure(): void {
        $this
                // the command description shown when running "php bin/console list"
                ->setDescription('Calculate Revenue .')
                // the command help shown when running the command with the "--help" option
                ->setHelp('This command allows you to Calculate Revenue...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln('Calculate Revenue ============>');
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        $data = [
            "date" => $yesterday,
            "status" => RunsheetStatusEnum::RUNSHEET_CLOSED->name
        ];
        $runsheets = $this->em->getRepository(Runsheet::class)->findRunsheet($data);
        //dd(count($runsheets));
        $runsheetIds = [];
        foreach ($runsheets as $runsheet) {
            $runsheetIds[] = $runsheet->getId();
        }
        $dataSearch = [
            "runsheetIds" => $runsheetIds,
            "status" => [PickUpStatus::DELIVERY_DELIVRED->name, PickUpStatus::DELIVERY_FINAL_RETURN->name]
        ];

        $listRecipes = $this->em->getRepository(PickUp::class)->recipeCalculate($dataSearch);

        $shipperId = null;
        $nbrTotalPickUps = 0;
        $totalPricePickUps = 0;
        $nbrPickUpsDelivred = 0;
        $nbrPickUpsReturn = 0;
        $response = [];
        foreach ($listRecipes as $recipe) {
            if ($shipperId != $recipe["id"]) {
                $shipperId = null;
                $nbrTotalPickUps = 0;
                $totalPricePickUps = 0;
                $nbrPickUpsDelivred = 0;
                $nbrPickUpsReturn = 0;
            }
            if ($recipe["status"] == "DELIVERY_DELIVRED") {
                $nbrTotalPickUps = $nbrTotalPickUps + $recipe["total_number_pick_ups"];
                $totalPricePickUps = $totalPricePickUps + $recipe["total_price"];
                $nbrPickUpsDelivred = $recipe["total_number_pick_ups"];
            } elseif ($recipe["status"] == "DELIVERY_FINAL_RETURN") {
                $nbrTotalPickUps = $nbrTotalPickUps + $recipe["total_number_pick_ups"];
                $totalPricePickUps = $totalPricePickUps + $recipe["total_price"];
                $nbrPickUpsReturn = $recipe["total_number_pick_ups"];
            }
            $response[$recipe["id"]] = [
                //ID
                "id" => $recipe["id"],
                //NOM
                "full_name" => $recipe["fullName"],
                "name_page" => $recipe["namePage"],
                "payment_method" => $recipe["paymentMethod"],
                "adresse" => $recipe["adresse"],
                //TOTAL
                "total_number_pick_up" => $nbrTotalPickUps,
                "total_price" => $totalPricePickUps,
                //CR
                //LIVRE
                "total_number_pick_up_delivred" => $nbrPickUpsDelivred,
                "total_delivery_fee" => $nbrPickUpsDelivred * $recipe["deliveryPrice"],
                //RETOUR
                "total_number_pick_up_return" => $nbrPickUpsReturn,
                "commission" => $nbrPickUpsReturn * $recipe["returnPrice"],
            ];

            $shipperId = $recipe["id"];
        }
//        dd($response);
//        dd("XXXXXXXXXXX");
        foreach ($response as $value) {
            $recipe = new Recipe();
            $shipper = $this->em->getRepository(User::class)->find($value["id"]);
            $recipe->setShipper($shipper);
            $recipe->setTotalNumberPickUp($value["total_number_pick_up"]);
            $recipe->setTotalPrice($value["total_price"]);
            $recipe->setTotalNumberPickUpDelivred($value["total_number_pick_up_delivred"]);
            $recipe->setTotalDeliveryFee($value["total_delivery_fee"]);
            $recipe->setTotalNumberPickUpReturn($value["total_number_pick_up_return"]);
            $recipe->setCommission($value["commission"]);
//            $recipe->setNetAmountSupplier($value[""]);
//            $recipe->setSupplierCredit($value[""]);
            $recipe->setPaymentMethod($value["payment_method"]);

            $this->em->persist($recipe);
            $this->em->flush();
        }

        $output->writeln("Creater Recipes { $yesterday } with SUCCESS." . "\n");

        return Command::SUCCESS;
    }
}
