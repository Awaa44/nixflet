<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    //ici on retourne un tableau (: array)
    public function findSerieCustom(int $offset, int $limit, string $status, \Datetime $date, ?float $vote = null): array
    {
        //on fait toutes les requetes dans l'entité série (alias s)
        $q = $this->createQueryBuilder('s')
            ->andWhere('s.status = :status OR s.firstAirDate <= :date')
            ->setParameter('status', $status)
            ->setParameter('date', $date)
            ->orderBy('s.popularity', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

            if($vote !== null){
                $q->orWhere('s.vote >= 8')
                    ->setParameter('vote', $vote);
            }
            return $q->getQuery()
                ->getResult();

    }

    //    /**
    //     * @return Serie[] Returns an array of Serie objects
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

    //    public function findOneBySomeField($value): ?Serie
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
