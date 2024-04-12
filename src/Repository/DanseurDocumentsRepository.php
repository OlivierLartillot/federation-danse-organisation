<?php

namespace App\Repository;

use App\Entity\DanseurDocuments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DanseurDocuments>
 *
 * @method DanseurDocuments|null find($id, $lockMode = null, $lockVersion = null)
 * @method DanseurDocuments|null findOneBy(array $criteria, array $orderBy = null)
 * @method DanseurDocuments[]    findAll()
 * @method DanseurDocuments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DanseurDocumentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DanseurDocuments::class);
    }

//    /**
//     * @return DanseurDocuments[] Returns an array of DanseurDocuments objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DanseurDocuments
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
