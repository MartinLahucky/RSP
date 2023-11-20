<?php

namespace App\Repository;

use App\Entity\Namitka;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Namitka>
 *
 * @method Namitka|null find($id, $lockMode = null, $lockVersion = null)
 * @method Namitka|null findOneBy(array $criteria, array $orderBy = null)
 * @method Namitka[]    findAll()
 * @method Namitka[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NamitkaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Namitka::class);
    }

//    /**
//     * @return Namitka[] Returns an array of Namitka objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Namitka
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
