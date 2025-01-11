<?php

namespace App\Repository;

use App\Entity\VetReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VetReport>
 */
class VetReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VetReport::class);
    }

    public function findByCriteria(array $criteria): array
    {
        $queryBuilder = $this->createQueryBuilder('vr')
            ->leftJoin('vr.animal', 'a')
            ->addSelect('a'); // Inclure les données liées à l'animal
    
        if (!empty($criteria['animalName'])) {
            $queryBuilder->andWhere('a.firstname LIKE :animalName')
                ->setParameter('animalName', '%' . $criteria['animalName'] . '%');
        }
    
        if (!empty($criteria['visitDate'])) {
            $queryBuilder->andWhere('vr.visitDate = :visitDate')
                ->setParameter('visitDate', $criteria['visitDate']);
        }
    
        return $queryBuilder->getQuery()->getResult();
    }
    

    //    /**
    //     * @return VetReport[] Returns an array of VetReport objects
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

    //    public function findOneBySomeField($value): ?VetReport
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
