<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Billet;
use App\Entity\Dab;

class BanqueService
{
    public function getSolde(Dab $dab): int
    {
        return array_sum(array_map(function (Billet $billet) {
            return $billet->getQuantite() * $billet->getMontantUnitaire();
        }, $dab->getBillets()->getValues()));
    }

    public function extraireDetailParBillet(Dab $dab): array
    {
        $nbBilletsDab = array_combine(
            array_map(function (Billet $billet) {
                return $billet->getMontantUnitaire();
            }, $dab->getBillets()->getValues()),
            array_map(function (Billet $billet) {
                return $billet->getQuantite();
            }, $dab->getBillets()->getValues()),
        );
        krsort($nbBilletsDab);

        return $nbBilletsDab;
    }
}
