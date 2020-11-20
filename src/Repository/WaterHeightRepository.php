<?php

namespace App\Repository;

use App\Entity\WaterHeight;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WaterHeight|null find($id, $lockMode = null, $lockVersion = null)
 * @method WaterHeight|null findOneBy(array $criteria, array $orderBy = null)
 * @method WaterHeight[]    findAll()
 * @method WaterHeight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WaterHeightRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WaterHeight::class);
    }

    // /**
    //  * @return WaterHeight[] Returns an array of WaterHeight objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WaterHeight
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
