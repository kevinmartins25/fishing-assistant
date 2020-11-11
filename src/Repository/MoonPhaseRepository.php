<?php

namespace App\Repository;

use App\Entity\MoonPhase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MoonPhase|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoonPhase|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoonPhase[]    findAll()
 * @method MoonPhase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoonPhaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoonPhase::class);
    }
}
