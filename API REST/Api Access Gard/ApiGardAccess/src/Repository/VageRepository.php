<?php

namespace App\Repository;

use App\Entity\Vage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vage[]    findAll()
 * @method Vage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vage::class);
    }

    // /**
    //  * @return Vage[] Returns an array of Vage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Vage
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
