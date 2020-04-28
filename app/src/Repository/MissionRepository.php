<?php

namespace App\Repository;

use App\Entity\Mission;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @method Mission|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mission|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mission[]    findAll()
 * @method Mission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MissionRepository extends ServiceEntityRepository
{
    private $security;

    public function __construct(
        ManagerRegistry $registry,
        Security $security
    ) {
        parent::__construct($registry, Mission::class);
        $this->security = $security;
    }

    // /**
    //  * @return Mission[] Returns an array of Mission objects
    //  */
    public function findAllByCriteria(array $criteria)
    {
        $qb = $this->createQueryBuilder('m');

        $qb->where($qb->expr()->isNotNull('m.client'))
            ->orderBy('m.serviceDate', 'DESC');

        $this->searchByCriteria($qb, $criteria);

        return $qb->getQuery();
    }

    private function searchByCriteria(QueryBuilder $qb, array $criteria)
    {
        if (!empty($criteria['isClient'])) {
            $this->filterByCurrentUser($qb);
        }

        if (!empty($criteria['startDate']) || !empty($criteria['endDate'])) {
            $this->filterByDate($qb, $criteria);
        }

        if (!empty($criteria['name'])) {
            $this->filterByClientName($qb, $criteria);
        }

        if (!empty($criteria['productName'])) {
            $qb->andWhere($qb->expr()->like('m.productName', $qb->expr()->literal("%".$criteria['productName']."%")));
        }

        if (!empty($criteria['vendorName'])) {
            $qb->andWhere($qb->expr()->like('m.vendorName', $qb->expr()->literal("%".$criteria['vendorName']."%")));
        }

        if (!empty($criteria['vendorEmail'])) {
            $qb->andWhere($qb->expr()->like('m.vendorEmail', $qb->expr()->literal("%".$criteria['vendorEmail']."%")));
        }

        if (!empty($criteria['destinationCountry'])) {
            $qb->andWhere($qb->expr()->eq('m.destinationCountry', $qb->expr()->literal($criteria['destinationCountry'])));
        }
    }

    private function filterByCurrentUser(QueryBuilder $qb): void
    {
        $qb->where('m.client = :clientId')
            ->setParameter('clientId', (string)$this->security->getUser()->getId());
    }

    private function filterByDate(QueryBuilder $qb, array $criteria): void
    {
        if (!empty($criteria['startDate'])) {
            $qb->andWhere($qb->expr()->gte('m.serviceDate', $qb->expr()->literal($criteria['startDate']->format('Y-m-d'))));
        }

        if (!empty($criteria['endDate'])) {
            $qb->andWhere($qb->expr()->lte('m.serviceDate', $qb->expr()->literal($criteria['endDate']->format('Y-m-d'))));
        }
    }

    private function filterByClientName(QueryBuilder $qb, array $criteria): void
    {
        if ($this->security->isGranted([User::ROLE_ADMIN])) {
            $qb->leftJoin('m.client', 'u')
                ->andWhere($qb->expr()->like('u.username', $qb->expr()->literal('%'.$criteria['name'].'%')));
        }
    }
}
