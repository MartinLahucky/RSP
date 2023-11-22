<?php

namespace App\Repository;

use App\Entity\Posudek;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Posudek>
 *
 * @method Posudek|null find($id, $lockMode = null, $lockVersion = null)
 * @method Posudek|null findOneBy(array $criteria, array $orderBy = null)
 * @method Posudek[]    findAll()
 * @method Posudek[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PosudekRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posudek::class);
    }

//    /**
//     * @return Posudek[] Returns an array of Posudek objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Posudek
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
