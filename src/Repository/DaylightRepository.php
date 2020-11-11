<?php

namespace App\Repository;

use App\Entity\Daylight;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Daylight|null find($id, $lockMode = null, $lockVersion = null)
 * @method Daylight|null findOneBy(array $criteria, array $orderBy = null)
 * @method Daylight[]    findAll()
 * @method Daylight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DaylightRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Daylight::class);
    }
}
