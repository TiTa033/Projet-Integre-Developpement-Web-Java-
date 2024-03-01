<?php

namespace App\Repository;


use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findByName($search)
    {
        $queryBuilder = $this->createQueryBuilder("e")
            ->where("e.name LIKE :search")
            ->setParameter("search", "%{$search}%");

        return $queryBuilder->getQuery()->getResult();
    }

    public function findByDateRange(\DateTime $start, \DateTime $end)
{
    return $this->createQueryBuilder('e')
        ->where('e.startDate BETWEEN :start AND :end OR e.endDate BETWEEN :start AND :end')
        ->setParameter('start', $start)
        ->setParameter('end', $end)
        ->getQuery()
        ->getResult();
}


public function findAllEvents()
{
    return $this->createQueryBuilder('e')
        ->getQuery()
        ->getResult();
}


    /**
     * @return Event[] Returns an array of Event objects with the given status
     */
    public function findByStatus($status)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getResult()
        ;
    }
}