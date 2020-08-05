<?php

namespace App\Repository;

use App\Entity\Tuteurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tuteurs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tuteurs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tuteurs[]    findAll()
 * @method Tuteurs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TuteursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tuteurs::class);
    }

    // /**
    //  * @return Tuteurs[] Returns an array of Tuteurs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tuteurs
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
