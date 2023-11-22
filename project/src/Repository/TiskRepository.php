<?php

namespace App\Repository;

use App\Entity\Tisk;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tisk>
 *
 * @method Tisk|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tisk|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tisk[]    findAll()
 * @method Tisk[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TiskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tisk::class);
    }

//    /**
//     * @return Tisk[] Returns an array of Tisk objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Tisk
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
