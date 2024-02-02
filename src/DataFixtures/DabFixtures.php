<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Billet;
use App\Entity\Dab;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DabFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = [
            [
                'dab-1',
                '1, Rue de la Fontaine Dorée',
                '2023-02-16',
                [10 => 25, 20 => 20, 50 => 8], // total de 1050 €
            ],
            [
                'dab-2',
                '2458 Avenue du Maréchal Joffre',
                '2023-02-22',
                [20 => 8, 50 => 32, 100 => 45],
            ],
            [
                'dab-3',
                '16 Impasse de la Plage',
                '2023-02-05',
                [10 => 108, 50 => 46, 200 => 9],
            ],
        ];

        foreach ($data as $dabData) {
            // création d'une instance de Dab
            $dab = new Dab();
            $dab->setName($dabData[0]);
            $dab->setAdresse($dabData[1]);
            $dab->setRechargement($dabData[2] ? new \DateTime($dabData[2]) : null);
            // gestion des quantités de billets du DAB
            foreach ($dabData[3] as $montant => $nbBillet) {
                // création d'une instance de Billet
                $billet = new Billet();
                $billet->setMontantUnitaire($montant);
                $billet->setQuantite($nbBillet);
                // ajout des billet au DAB
                $dab->addBillet($billet);
            }

            // sauvegarde du DAB
            $manager->persist($dab);
            // conservation d'une référence du DAB
            $this->addReference($dab->getName(), $dab);
        }

        $manager->flush();
    }
}
