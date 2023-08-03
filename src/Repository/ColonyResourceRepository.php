<?php

namespace App\Repository;

use App\Entity\ColonyResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ColonyResource>
 *
 * @method ColonyResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method ColonyResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method ColonyResource[]    findAll()
 * @method ColonyResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColonyResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ColonyResource::class);
    }

//    /**
//     * @return ColonyResource[] Returns an array of ColonyResource objects
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

//    public function findOneBySomeField($value): ?ColonyResource
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
