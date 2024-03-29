<?php

namespace App\Repository;

use App\Entity\LicenceComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LicenceComment>
 *
 * @method LicenceComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method LicenceComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method LicenceComment[]    findAll()
 * @method LicenceComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LicenceCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LicenceComment::class);
    }

//    /**
//     * @return LicenceComment[] Returns an array of LicenceComment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LicenceComment
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
