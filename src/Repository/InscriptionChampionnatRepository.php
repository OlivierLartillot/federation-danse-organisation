<?php

namespace App\Repository;

use App\Entity\InscriptionChampionnat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InscriptionChampionnat>
 *
 * @method InscriptionChampionnat|null find($id, $lockMode = null, $lockVersion = null)
 * @method InscriptionChampionnat|null findOneBy(array $criteria, array $orderBy = null)
 * @method InscriptionChampionnat[]    findAll()
 * @method InscriptionChampionnat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionChampionnatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InscriptionChampionnat::class);
    }

    //    /**
    //     * @return InscriptionChampionnat[] Returns an array of InscriptionChampionnat objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?InscriptionChampionnat
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
