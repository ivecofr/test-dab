<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Billet;
use App\Entity\Retrait;

class RetraitFactory
{
    /**
     * @param array $billets
     * un tableau clé valeurs
     * les clés sont les montants des billets
     * les valeurs sont les quantités des billets
     */
    public static function create(array $billets): Retrait
    {
        // création d'une instance de Retrait
        $retrait = new Retrait();
        $retrait->setDate(new \DateTimeImmutable());
        // gestion des quantités de billets du DAB
        foreach ($billets as $montant => $nbBillet) {
            // si aucun billet
            if (0 === $nbBillet) {
                continue;
            }
            // création d'une instance de Billet
            $billet = new Billet();
            $billet->setMontantUnitaire($montant);
            $billet->setQuantite($nbBillet);
            // ajout des billet au retrait
            $retrait->addBillet($billet);
        }

        return $retrait;
    }
}
