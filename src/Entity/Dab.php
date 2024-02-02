<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DabRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'dab')]
#[ORM\Entity(repositoryClass: DabRepository::class)]
class Dab
{
    #[ORM\Id]
    #[ORM\Column(name: 'dab_id', type: Types::INTEGER)]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(name: 'dab_name', type: Types::STRING, length: 20, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(name: 'dab_adresse', type: Types::TEXT, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(name: 'dab_rechargement', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $rechargement = null;

    #[ORM\ManyToMany(targetEntity: Billet::class, cascade:['persist', 'remove'])]
    #[ORM\JoinTable(name: 'dab_bil')]
    #[ORM\JoinColumn(name: 'id_dab', referencedColumnName: 'dab_id')]
    #[ORM\InverseJoinColumn(name: 'id_bil', referencedColumnName: 'bil_id', unique: true)]
    private Collection $billets;

    #[ORM\OneToMany(targetEntity: Retrait::class, mappedBy: 'dab', cascade:['persist', 'remove'])]
    private Collection $retraits;

    public function __construct()
    {
        $this->billets = new ArrayCollection();
        $this->retraits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): Dab
    {
        $this->name = $name;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): Dab
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getRechargement(): ?\DateTime
    {
        return $this->rechargement;
    }

    public function setRechargement(?\DateTime $rechargement): Dab
    {
        $this->rechargement = $rechargement;

        return $this;
    }

    public function getBillets(): Collection
    {
        return $this->billets;
    }

    public function addBillet(Billet $billet): Dab
    {
        if (!$this->billets->contains($billet)) {
            $this->billets[] = $billet;
        }

        return $this;
    }

    public function removeBillet(Billet $billet): Dab
    {
        if ($this->billets->contains($billet)) {
            $this->billets->removeElement($billet);
        }

        return $this;
    }

    public function getRetraits(): Collection
    {
        return $this->retraits;
    }

    public function addRetrait(Retrait $retrait): Dab
    {
        if (!$this->retraits->contains($retrait)) {
            $this->retraits[] = $retrait;
            $retrait->setDab($this);
        }

        return $this;
    }

    public function removeRetrait(Retrait $retrait): Dab
    {
        if ($this->retraits->contains($retrait)) {
            $this->retraits->removeElement($retrait);
        }

        return $this;
    }
}
