<?Php

namespace App\Repository;

use App\Entity\EventParticipant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Event;
use App\Entity\User;

/**
 * @method EventParticipant|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventParticipant|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventParticipant[]    findAll()
 * @method EventParticipant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventParticipant::class);
    }

    /**
     * @return EventParticipant[]
     */
    public function findByEvent(Event $event): array
    {
        return $this->createQueryBuilder('ep')
            ->where('ep.event = :event')
            ->setParameter('event', $event)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return EventParticipant|null
     */
    public function findOneByEventAndUser(Event $event, User $user): ?EventParticipant
    {
        return $this->createQueryBuilder('ep')
            ->where('ep.event = :event')
            ->andWhere('ep.user = :user')
            ->setParameter('event', $event)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }
}