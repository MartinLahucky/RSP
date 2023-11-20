<?php

namespace App\Repository;

use App\Entity\KomentarClanek;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<KomentarClanek>
 *
 * @method KomentarClanek|null find($id, $lockMode = null, $lockVersion = null)
 * @method KomentarClanek|null findOneBy(array $criteria, array $orderBy = null)
 * @method KomentarClanek[]    findAll()
 * @method KomentarClanek[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KomentarClanekRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KomentarClanek::class);
    }

//    /**
//     * @return KomentarClanek[] Returns an array of KomentarClanek objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('k')
//            ->andWhere('k.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('k.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?KomentarClanek
//    {
//        return $this->createQueryBuilder('k')
//            ->andWhere('k.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
