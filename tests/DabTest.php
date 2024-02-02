<?php

declare(strict_types = 1);

namespace App\Tests;

use App\Entity\Billet;
use App\Entity\Dab;
use App\Entity\Retrait;
use App\Exception\NotEnoughCashException;
use App\Service\DabService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class DabTest
 */
class DabTest extends KernelTestCase
{
    public function testViderUnDab(): void
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();
        $dabRepo = $container->get("doctrine")->getRepository(Dab::class);
        $dabService = $container->get(DabService::class);

        // récupération du DAB par son ID
        $dab = $dabRepo->findWithBillets("dab-1"); // montant total de 1050 €

        $retrait = $dabService->faireRetrait($dab, 270);
        $this->verifieRetrait($retrait, [50 => 5, 20 => 1]);
        $this->verifieSoldeDab($dab, 780);

        $retrait = $dabService->faireRetrait($dab, 440);
        $this->verifieRetrait($retrait, [50 => 3, 20 => 14, 10 => 1]);
        $this->verifieSoldeDab($dab, 340);

        $retrait = $dabService->faireRetrait($dab, 190);
        $this->verifieRetrait($retrait, [20 => 5, 10 => 9]);
        $this->verifieSoldeDab($dab, 150);

        $retrait = $dabService->faireRetrait($dab, 50);
        $this->verifieRetrait($retrait, [10 => 5]);
        $this->verifieSoldeDab($dab, 100);

        $retrait = $dabService->faireRetrait($dab, 30);
        $this->verifieRetrait($retrait, [10 => 3]);
        $this->verifieSoldeDab($dab, 70);

        $retrait = $dabService->faireRetrait($dab, 70);
        $this->verifieRetrait($retrait, [10 => 7]);
        $this->verifieSoldeDab($dab, 0);
    }

    public function testPasAssezArgent(): void
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();
        $dabRepo = $container->get("doctrine")->getRepository(Dab::class);
        $dabService = $container->get(DabService::class);

        // récupération du DAB par son ID
        $dab = $dabRepo->findWithBillets("dab-1"); // montant restant = 0 €

        $this->expectException(NotEnoughCashException::class);
        $retrait = $dabService->faireRetrait($dab, 200);
    }

    /**
     * @param Retrait|null $retrait
     * @param array        $resultatsParBillet
     */
    private function verifieRetrait(?Retrait $retrait, array $resultatsParBillet): void
    {
        // récupération des quantités de billets par sous la forme d'un tableau clé et valeurs
        $nbBilletsRetrait = array_combine(
            array_map(function (Billet $billet) {
                return $billet->getMontantUnitaire();
            }, $retrait->getBillets()->getValues()),
            array_map(function (Billet $billet) {
                return $billet->getQuantite();
            }, $retrait->getBillets()->getValues()),
        );
        // parcourt des billets à trouver
        foreach ($resultatsParBillet as $montant => $nbBillet) {
            // récupération du nb de billet dans le retrait
            $nbBilletDansRetrait = array_key_exists($montant, $nbBilletsRetrait) ? $nbBilletsRetrait[$montant] : null;
            $this->assertEquals($nbBillet, $nbBilletDansRetrait, "Il devrait y avoir {$nbBillet} billet(s) de {$montant} €, {$nbBilletDansRetrait} trouvé(s)");
        }
    }

    /**
     * @param Dab $dab
     * @param int $montantAttendu
     */
    private function verifieSoldeDab(Dab $dab, int $montantAttendu): void
    {
        // récupération des montant calculé par billets
        $montantParBillet = array_map(function (Billet $billet) {
            return $billet->getQuantite() * $billet->getMontantUnitaire();
        }, $dab->getBillets()->getValues());
        // calcul de la somme de tous les montants par billet
        $sommeDab = array_sum($montantParBillet);

        $this->assertEquals($montantAttendu, $sommeDab, "Il devrait rester {$montantAttendu} € dans le DAB, {$sommeDab} € trouvé(s)");
    }
}
