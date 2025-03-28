<?php

namespace App\Repository;

use App\Entity\Championship;
use App\Entity\Club;
use App\Entity\InjuryTab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
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

    public function getClubsFrom(string $championship, int $season):ArrayCollection
    {
        switch($championship)
        {
            case "ligue-1" :
                if($season == 2022)
                {
                    return $this->getLigue1ClubsFrom2022();
                }
                else
                {
                    return $this->getLigue1ClubsFrom2023();
                }
            case "premier-league" :
                if($season == 2022)
                {
                    return $this->getPLClubsFrom2022();
                }
                else
                {
                    return $this->getPLClubsFrom2023();
                }
            case "serie-a" :
                if($season == 2022)
                {
                    return $this->getSerieAClubsFrom2022();
                }
                else
                {
                    return $this->getSerieAClubsFrom2023();
                }
            case "laliga" :
                if($season == 2022)
                {
                    return $this->getLaLigaClubsFrom2022();
                }
                else
                {
                    return $this->getLaLigaClubsFrom2023();
                }

            default :
                return $this->getLigue1ClubsFrom2023();

        }

    }

    public function getLigue1ClubsFrom2022():ArrayCollection
    {
        $clubs = new ArrayCollection();
        foreach (['ac-ajaccio', 'angers-sco', 'aj-auxerre', 'stade-brestois-29', 'clermont-foot-63', 'rc-lens', 'lille-osc', 'fc-lorient', 'olympique-lyonnais', 'olympique-de-marseille', 'as-monaco', 'montpellier-hsc', 'fc-nantes', 'ogc-nice', 'paris-saint-germain', 'stade-de-reims', 'stade-rennais', 'rc-strasbourg', 'toulouse-fc'] as $slug)
        {
            $club = $this->findOneBy(['slug' => $slug]);
            if($club != null)
            {
                $clubs->add($club);
            }
        }
        return $clubs;
    }

    public function getPLClubsFrom2022():ArrayCollection
    {
        $clubs = new ArrayCollection();
        foreach (['arsenal-fc', 'aston-villa-fc', 'afc-bournemouth', 'brentford-fc', 'brighton-and-hove-albion', 'chelsea-fc', 'crystal-palace-fc', 'everton fc', 'leeds-united', 'leicester-fc', 'liverpool-fc', 'manchester-city-fc', 'manchester united', 'newcastle-united', 'nottingham-forest-fc', 'sheffield-united-fc', 'southampton-fc', 'tottenham', 'west-ham', 'wolves'] as $slug)
        {
            $club = $this->findOneBy(['slug' => $slug]);
            if($club != null)
            {
                $clubs->add($club);
            }
        }
        return $clubs;
    }

    public function getSerieAClubsFrom2022():ArrayCollection
    {
        $clubs = new ArrayCollection();
        foreach (['atalanta-bergamasca', 'bologna-fc-1909', 'us-cremonese', 'empoli-fc', 'fiorentina-acf', 'hellas-verona-fc', 'internazionale-fc', 'juventus-fc', 'ss-lazio', 'ac-milan', 'ac-monza', 'napoli-societa', 'roma-as', 'us-salernitana', 'sampdoria-uc', 'sassuolo-calcio-us', 'spezia-calcio', 'torino-fc', 'udinese-calcio', 'us-lecce'] as $slug)
        {
            $club = $this->findOneBy(['slug' => $slug]);
            if($club != null)
            {
                $clubs->add($club);
            }
        }
        return $clubs;
    }

    public function getLaLigaClubsFrom2022():ArrayCollection
    {
        $clubs = new ArrayCollection();
        foreach (['almeria', 'athletic-bilbao', 'atletico-madrid', 'fc-barcelone', 'betis-seville', 'cadiz-cf', 'celta-de-vigo', 'elche-cf', 'rcd-espanyol', 'getafe-fc', 'girona-fc', 'rcd-mallorca', 'ca-osasuna', 'rayo-vallecano', 'real-madrid-cf', 'real-sociedad', 'sevilla-fc', 'valencia-cf', 'real-valladolid-cf', 'villarreal-cf'] as $slug)
        {
            $club = $this->findOneBy(['slug' => $slug]);
            if($club != null)
            {
                $clubs->add($club);
            }
        }
        return $clubs;
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
