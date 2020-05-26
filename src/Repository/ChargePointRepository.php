<?php

namespace App\Repository;

use App\Entity\ChargePoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChargePoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChargePoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChargePoint[]    findAll()
 * @method ChargePoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChargePointRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, ChargePoint::class);

        $this->manager = $manager;
    }


    public function saveChargePoint($identity, $cpo): void
    {
        $newChargePoint = new ChargePoint();

        $newChargePoint
            ->setIdentity($identity)
            ->setCpo($cpo);

        $this->manager->persist($newChargePoint);
        $this->manager->flush();
    }

    public function updateChargePoint(ChargePoint $chargePoint): ChargePoint
    {
        $this->manager->persist($chargePoint);
        $this->manager->flush();

        return $chargePoint;
    }

    public function deleteChargePoint(ChargePoint $chargePoint): void
    {
        $this->manager->remove($chargePoint);
        $this->manager->flush();
    }

    // /**
    //  * @return ChargePoint[] Returns an array of ChargePoint objects
    //  */
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $value
     * @return ChargePoint|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneBySomeField($value): ?ChargePoint
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
