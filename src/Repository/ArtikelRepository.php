<?php

namespace App\Repository;

use App\Entity\Artikel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Artikel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Artikel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Artikel[]    findAll()
 * @method Artikel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtikelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Artikel::class);
    }

    // /**
    //  * @return Artikel[] Returns an array of Artikel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Artikel
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
