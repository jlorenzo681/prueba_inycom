<?php

namespace App\Repository;

use App\Entity\Organization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Organization|null find($id, $lockMode = null, $lockVersion = null)
 * @method Organization|null findOneBy(array $criteria, array $orderBy = null)
 * @method Organization[]    findAll()
 * @method Organization[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganizationRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Organization::class);

        $this->manager = $manager;
    }


    public function saveOrganization($name, $legalEntity): void
    {
        $newOrganization = new Organization();

        $newOrganization
            ->setName($name)
            ->setLegalEntity($legalEntity);

        $this->manager->persist($newOrganization);
        $this->manager->flush();
    }

    public function updateOrganization(Organization $organization): Organization
    {
        $this->manager->persist($organization);
        $this->manager->flush();

        return $organization;
    }

    public function deleteOrganization(Organization $organization): void
    {
        $this->manager->remove($organization);
        $this->manager->flush();
    }

    // /**
    //  * @return Organization[] Returns an array of Organization objects
    //  */
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $value
     * @return Organization|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneBySomeField($value): ?Organization
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
