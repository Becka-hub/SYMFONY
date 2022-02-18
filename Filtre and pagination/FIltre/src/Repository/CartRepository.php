<?php

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    // /**
    //  * @return Cart[] Returns an array of Cart objects
    //  */

    public function searchCart($criteria)
    {
        return $this->createQueryBuilder('c')  
            ->leftJoin('c.city','city')
            ->where('city.name=:cityName')
            ->setParameter('cityName', $criteria['city']->getName())
            ->andWhere('c.color=:color')
            ->setParameter('color', $criteria['color'])
            ->andWhere('c.carburant=:carburant')
            ->setParameter('carburant', $criteria['carburant'])
            ->andWhere('c.price > :minimumPrice')
            ->setParameter('minimumPrice', $criteria['minimumPrice'])
            ->andWhere('c.price < :maximumPrice')
            ->setParameter('maximumPrice', $criteria['maximumPrice'])
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
   

    /*
    public function findOneBySomeField($value): ?Cart
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
