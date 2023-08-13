<?php

namespace App\Repository;

use App\Entity\Fleet;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fleet>
 *
 * @method Fleet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fleet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fleet[]    findAll()
 * @method Fleet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FleetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fleet::class);
    }

    /**
     * @return Fleet[] Returns an array of Fleet objects
     */
    public function getMovingBySystemId(int $id)
    {
        return $this->createQueryBuilder('f')
            ->leftJoin('f.position', 'p')
            ->leftJoin('p.star_system', 's')
            ->andWhere('s.id = :id')
            ->andWhere('f.destination IS NOT NULL')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Fleet[] Returns an array of Fleet objects
     */
    public function getFinishedAction()
    {
        return $this->createQueryBuilder('f')
            ->leftJoin('f.player', 'p')
            ->andWhere('f.action_end IS NOT NULL')
            ->andWhere('f.action_end <= :now')
            ->setParameter('now', new DateTime())
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Fleet[] Returns an array of Fleet objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Fleet
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
