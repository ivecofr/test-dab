<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BilletRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'billet')]
#[ORM\Entity(repositoryClass: BilletRepository::class)]
class Billet
{
    #[ORM\Id]
    #[ORM\Column(name: 'bil_id', type: Types::INTEGER)]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(name: 'bil_montantunitaire', type: Types::INTEGER, nullable: false)]
    private ?int $montantUnitaire = null;

    #[ORM\Column(name: 'bil_quantite', type: Types::INTEGER, nullable: false)]
    private ?int $quantite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantUnitaire(): ?int
    {
        return $this->montantUnitaire;
    }

    public function setMontantUnitaire(int $montantUnitaire): Billet
    {
        $this->montantUnitaire = $montantUnitaire;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): Billet
    {
        $this->quantite = $quantite;

        return $this;
    }
}
