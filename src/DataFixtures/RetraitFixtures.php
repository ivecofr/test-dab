<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Dab;
use App\Service\RetraitFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RetraitFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $data = [
            'dab-1' => [
                ['2023-02-10', '4564 6832 1346 4589', [10 => 1, 20 => 3, 50 => 4]],
                ['2023-02-11', '4564 8654 0896 4785', [10 => 0, 20 => 6, 50 => 0]],
                ['2023-02-11', '1778 6832 0784 6366', [10 => 6, 20 => 1, 50 => 1]],
                ['2023-02-13', '5598 1454 2036 6690', [10 => 0, 20 => 0, 50 => 3]],
            ],
            'dab-2' => [
                ['2023-01-28', '8985 4284 5276 7338', [20 => 1, 50 => 4, 100 => 0]],
                ['2023-01-28', '6364 6832 2437 5793', [20 => 0, 50 => 5, 100 => 0]],
                ['2023-01-28', '9780 2512 7165 4866', [20 => 1, 50 => 2, 100 => 0]],
                ['2023-01-29', '4946 3839 1006 2237', [20 => 0, 50 => 0, 100 => 1]],
                ['2023-01-29', '1873 4128 9902 3701', [20 => 0, 50 => 1, 100 => 0]],
                ['2023-01-30', '4564 8576 9537 4589', [20 => 1, 50 => 0, 100 => 2]],
            ],
            'dab-3' => [
                ['2023-01-28', '3644 1010 5276 5672', [10 => 1, 50 => 1, 200 => 1]],
                ['2023-02-03', '3217 4377 2437 3439', [10 => 0, 50 => 0, 200 => 2]],
                ['2023-02-05', '5390 2512 7165 1249', [10 => 6, 50 => 1, 200 => 0]],
            ],
        ];

        foreach ($data as $refDab => $retDataParDab) {
            // récupération de la référence du DAB correspondant
            /** @var Dab $dab */
            $dab = $this->getReference($refDab);
            // parcourt des données des retrait de ce DAB
            foreach ($retDataParDab as $retData) {
                // création d'une instance de Retrait
                $retrait = RetraitFactory::create($retData[2]);
                $retrait->setDate($retData[0] ? new \DateTimeImmutable($retData[0]) : null);
                $retrait->setNumCarte($retData[1]);
                // ajout du retrait au DAB
                $dab->addRetrait($retrait);
                // sauvegarde du DAB
                $manager->persist($dab);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DabFixtures::class,
        ];
    }
}
