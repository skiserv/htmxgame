<?php

namespace App\Repository;

use App\Entity\StellarObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StellarObject>
 *
 * @method StellarObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method StellarObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method StellarObject[]    findAll()
 * @method StellarObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StellarObjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StellarObject::class);
    }

//    /**
//     * @return StellarObject[] Returns an array of StellarObject objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?StellarObject
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
