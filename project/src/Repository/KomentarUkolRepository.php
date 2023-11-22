<?php

namespace App\Repository;

use App\Entity\KomentarUkol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<KomentarUkol>
 *
 * @method KomentarUkol|null find($id, $lockMode = null, $lockVersion = null)
 * @method KomentarUkol|null findOneBy(array $criteria, array $orderBy = null)
 * @method KomentarUkol[]    findAll()
 * @method KomentarUkol[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KomentarUkolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KomentarUkol::class);
    }

//    /**
//     * @return KomentarUkol[] Returns an array of KomentarUkol objects
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

//    public function findOneBySomeField($value): ?KomentarUkol
//    {
//        return $this->createQueryBuilder('k')
//            ->andWhere('k.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
