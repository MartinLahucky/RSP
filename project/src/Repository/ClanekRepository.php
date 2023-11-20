<?php

namespace App\Repository;

use App\Entity\Clanek;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Clanek>
 *
 * @method Clanek|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clanek|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clanek[]    findAll()
 * @method Clanek[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClanekRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clanek::class);
    }

//    /**
//     * @return Clanek[] Returns an array of Clanek objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Clanek
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
