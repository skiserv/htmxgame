<?php

namespace App\Repository;

use App\Entity\ColonyBuilding;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ColonyBuilding>
 *
 * @method ColonyBuilding|null find($id, $lockMode = null, $lockVersion = null)
 * @method ColonyBuilding|null findOneBy(array $criteria, array $orderBy = null)
 * @method ColonyBuilding[]    findAll()
 * @method ColonyBuilding[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColonyBuildingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ColonyBuilding::class);
    }

//    /**
//     * @return ColonyBuilding[] Returns an array of ColonyBuilding objects
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

//    public function findOneBySomeField($value): ?ColonyBuilding
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
