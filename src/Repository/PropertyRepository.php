<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Property>
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    //    /**
    //     * @return Property[] Returns an array of Property objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Property
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findBySearchCriteria(array $criteria): array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.agence', 'a')
            ->addSelect('a');

        if (!empty($criteria['title'])) {
            $qb->andWhere('p.title LIKE :title')
                ->setParameter('title', '%'.$criteria['title'].'%');
        }

        if (!empty($criteria['minPrice'])) {
            $qb->andWhere('p.price >= :minPrice')
                ->setParameter('minPrice', $criteria['minPrice']);
        }

        if (!empty($criteria['maxPrice'])) {
            $qb->andWhere('p.price <= :maxPrice')
                ->setParameter('maxPrice', $criteria['maxPrice']);
        }

        if (!empty($criteria['type'])) {
            $qb->andWhere('p.type = :type')
                ->setParameter('type', $criteria['type']);
        }

        if (!empty($criteria['city'])) {
            $qb->andWhere('p.city LIKE :city')
                ->setParameter('city', '%'.$criteria['city'].'%');
        }

        if (!empty($criteria['neighborhood'])) {
            $qb->andWhere('p.neighborhood LIKE :neighborhood')
                ->setParameter('neighborhood', '%'.$criteria['neighborhood'].'%');
        }

        if (!empty($criteria['minRooms'])) {
            $qb->andWhere('p.rooms >= :minRooms')
                ->setParameter('minRooms', $criteria['minRooms']);
        }

        if (!empty($criteria['minBeds'])) {
            $qb->andWhere('p.beds >= :minBeds')
                ->setParameter('minBeds', $criteria['minBeds']);
        }

        if (!empty($criteria['minBath'])) {
            $qb->andWhere('p.bath >= :minBath')
                ->setParameter('minBath', $criteria['minBath']);
        }

        if (!empty($criteria['minSurface'])) {
            $qb->andWhere('p.surface >= :minSurface')
                ->setParameter('minSurface', $criteria['minSurface']);
        }

        if (!empty($criteria['propertyStatus'])) {
            $qb->andWhere('p.propertyStatus = :propertyStatus')
                ->setParameter('propertyStatus', $criteria['propertyStatus']);
        }

        if (!empty($criteria['agence'])) {
            $qb->andWhere('a.id = :agenceId')
                ->setParameter('agenceId', $criteria['agence']);
        }

        if (!empty($criteria['promotion'])) {
            $qb->andWhere('p.promotion = :promotion')
                ->setParameter('promotion', $criteria['promotion']);
        }

        return $qb->getQuery()->getResult();
    }
}
