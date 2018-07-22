<?php

namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ticket::class);
    }
    /**
     * @return Ticket[]
     */
    public function findAllTickets()
    {
        $tickets = $this->createQueryBuilder('t')
            ->Where('t.date_end >= :today')
            ->andWhere('t.date <= :today')
            ->setParameter('today', new \DateTime());

        return $tickets
            ->getQuery()
            ->getResult();
    }

    public function findByDate($value)
    {
        $tickets = $this->createQueryBuilder('t')
            ->orderBy('t.title', 'asc');

        if (isset($value['showDate'])) {
            $tickets->andWhere('t.date_end >= :showDate')
                ->andWhere('t.date <= :showDate')
                ->setParameter('showDate', $value['showDate']);

        }

        return $tickets
            ->getQuery()
            ->getResult();
    }

}
