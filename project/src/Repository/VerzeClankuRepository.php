<?php

namespace App\Repository;

use App\Entity\VerzeClanku;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VerzeClanku>
 *
 * @method VerzeClanku|null find($id, $lockMode = null, $lockVersion = null)
 * @method VerzeClanku|null findOneBy(array $criteria, array $orderBy = null)
 * @method VerzeClanku[]    findAll()
 * @method VerzeClanku[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VerzeClankuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VerzeClanku::class);
    }

//    /**
//     * @return VerzeClanku[] Returns an array of VerzeClanku objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VerzeClanku
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
