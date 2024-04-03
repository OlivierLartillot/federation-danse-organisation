<?php

namespace App\Repository;

use App\Entity\Championship;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Championship>
 *
 * @method Championship|null find($id, $lockMode = null, $lockVersion = null)
 * @method Championship|null findOneBy(array $criteria, array $orderBy = null)
 * @method Championship[]    findAll()
 * @method Championship[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChampionshipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Championship::class);
    }

    /**
     * @return Championship[] Returns an array of Championship objects
     */
    public function allChampionshipsByCurrentSeason(): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('App\Entity\Season', 'season', 'WITH', 'c.season = season.id')
            ->andWhere('season.isCurrentSeason = :currentSeason')
            ->setParameter('currentSeason', true)
            ->orderBy('c.championshipDate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    //    public function findOneBySomeField($value): ?Championship
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
