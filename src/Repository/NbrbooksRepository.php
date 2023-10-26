<?php

namespace App\Repository;

use App\Entity\Nbrbooks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Nbrbooks>
 *
 * @method Nbrbooks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nbrbooks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nbrbooks[]    findAll()
 * @method Nbrbooks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NbrbooksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nbrbooks::class);
    }

//    /**
//     * @return Nbrbooks[] Returns an array of Nbrbooks objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Nbrbooks
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
