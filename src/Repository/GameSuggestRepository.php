<?php

namespace App\Repository;

use App\Entity\GameSuggest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GameSuggest|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameSuggest|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameSuggest[]    findAll()
 * @method GameSuggest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameSuggestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GameSuggest::class);
    }

    // /**
    //  * @return GameSuggest[] Returns an array of GameSuggest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GameSuggest
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
