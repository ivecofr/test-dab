<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RetraitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'retrait')]
#[ORM\Entity(repositoryClass: RetraitRepository::class)]
class Retrait
{
    #[ORM\Id]
    #[ORM\Column(name: 'ret_id', type: Types::INTEGER)]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(name: 'ret_date', type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(name: 'ret_numcarte', type: Types::STRING, length: 50, nullable: true)]
    private ?string $numCarte = null;

    #[ORM\ManyToMany(targetEntity: Billet::class, cascade:['persist', 'remove'])]
    #[ORM\JoinTable(name: 'ret_bil')]
    #[ORM\JoinColumn(name: 'id_ret', referencedColumnName: 'ret_id')]
    #[ORM\InverseJoinColumn(name: 'id_bil', referencedColumnName: 'bil_id', unique: true)]
    private Collection $billets;

    #[ORM\ManyToOne(targetEntity: Dab::class, inversedBy: 'retraits')]
    #[ORM\JoinColumn(name: 'ret_dab', referencedColumnName: 'dab_id')]
    private ?Dab $dab = null;

    public function __construct()
    {
        $this->billets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): Retrait
    {
        $this->date = $date;

        return $this;
    }

    public function getNumCarte(): ?string
    {
        return $this->numCarte;
    }

    public function setNumCarte(?string $numCarte): Retrait
    {
        $this->numCarte = $numCarte;

        return $this;
    }

    public function getBillets(): Collection
    {
        return $this->billets;
    }

    public function addBillet(Billet $billet): Retrait
    {
        if (!$this->billets->contains($billet)) {
            $this->billets[] = $billet;
        }

        return $this;
    }

    public function removeBillet(Billet $billet): Retrait
    {
        if ($this->billets->contains($billet)) {
            $this->billets->removeElement($billet);
        }

        return $this;
    }

    public function getDab(): ?Dab
    {
        return $this->dab;
    }

    public function setDab(Dab $dab): Retrait
    {
        $this->dab = $dab;

        return $this;
    }
}
