<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Dab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dab::class);
    }

    public function findWithBillets(string $nomDab): Dab
    {
        $qb = $this->createQueryBuilder('dab');

        $qb
            ->addSelect('billet')
            ->leftJoin('dab.billets', 'billet')
            ->where($qb->expr()->eq('dab.name', ':nomDab'))
            ->setParameter('nomDab', $nomDab)
        ;

        return $qb->getQuery()->getSingleResult();
    }
}
