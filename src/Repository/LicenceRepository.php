<?php

namespace App\Repository;

use App\Entity\Licence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Licence>
 *
 * @method Licence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Licence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Licence[]    findAll()
 * @method Licence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LicenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Licence::class);
    }

//    /**
//     * @return Licence[] Returns an array of Licence objects
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


    public function findByExampleField(): array
    {
        return $this->createQueryBuilder('l')
        ->select(max('l.dossard'))
            ->getQuery()
            ->getResult()
        ;
    }



    public function findDossardGroupByCategories($currentSeason): array
    {
        return $this->createQueryBuilder('l')
            ->innerJoin('App\Entity\club', 'club', 'WITH', 'l.club = club.id')
            ->innerJoin('App\Entity\category', 'category', 'WITH', 'l.category = category.id')
            ->andWhere('l.season = :season')
            ->setParameter('season', $currentSeason)
            ->addOrderBy('club.name', 'ASC')
            ->addOrderBy('category.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findValidateLicencesByCurrentSeasonAndClubOrderByCategories($currentSeason, $club = null, $validate = false, $categorie = null): array
    {
        $request =  $this->createQueryBuilder('l')
            ->innerJoin('App\Entity\category', 'category', 'WITH', 'l.category = category.id')
            ->innerJoin('App\Entity\club', 'club', 'WITH', 'l.club = club.id')
            ->andWhere('l.season = :season')
            ->setParameter('season', $currentSeason);

            // si c est un club on ne lui renvoi que ce qui es dans son club
            if ($club != null) {
                $request = $request
                ->andWhere('l.club = :club')
                ->setParameter('club', $club);
            }
            // si tu veux que les licence validée alors il faut true
            if ($validate) {
                $request = $request
                ->andWhere('l.status = :status')
                ->setParameter('status', 1);
            }
            if ($categorie) {
                $request = $request
                ->andWhere('l.category = :categorie')
                ->setParameter('categorie', $categorie);
            }

        $request = $request
            ->OrderBy('club.name', 'ASC')
            ->addorderBy('category.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        return $request;
    }

//    public function findOneBySomeField($value): ?Licence
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
