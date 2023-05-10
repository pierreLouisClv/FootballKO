<?php

namespace App\Repository;

use App\Entity\Championship;
use App\Entity\Club;
use App\Entity\InjuryTab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Club>
 *
 * @method Club|null find($id, $lockMode = null, $lockVersion = null)
 * @method Club|null findOneBy(array $criteria, array $orderBy = null)
 * @method Club[]    findAll()
 * @method Club[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClubRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Club::class);
    }

    public function save(Club $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Club $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getClubsByStatus(Championship $championship, string $status):array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.championship = :championship')
            ->setParameter(':championship', $championship)
            ->andWhere('c.status = :status')
            ->setParameter(':status', $status)
            ->orderBy('c.lastInjuryUpdate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getClubsSortedByCityName(Championship $championship):array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.championship = :champ')
            ->setParameter(':champ', $championship)
            ->orderBy('t.cityName')
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return Club[] Returns an array of Club objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Club
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
