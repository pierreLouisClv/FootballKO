<?php

namespace App\Repository;

use App\Entity\Championship;
use App\Entity\Club;
use App\Entity\InjuryTab;
use App\InjuriesHandler\InjuryTabHandler;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InjuryTab>
 *
 * @method InjuryTab|null find($id, $lockMode = null, $lockVersion = null)
 * @method InjuryTab|null findOneBy(array $criteria, array $orderBy = null)
 * @method InjuryTab[]    findAll()
 * @method InjuryTab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InjuryTabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,
                                public ClubRepository $clubRepository,
                                public EntityManagerInterface $em)
    {
        parent::__construct($registry, InjuryTab::class);
    }

    public function save(InjuryTab $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InjuryTab $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function absentFromTheLastDay(InjuryTab $injuryTab) :Collection
    {
        $day = $injuryTab->getDay();
        if($day > 1) {
            $lastInjuryTab = $this->findOneBy(['day' => $day - 1]);
            if($lastInjuryTab != null){
                return $lastInjuryTab->getAbsent();
            }
        }

        return new ArrayCollection();

    }

    public function getCurrentInjuryTabs(Championship $championship):Collection{
        $injuryTabs = new ArrayCollection();
        $day = $championship->getCurrentDay();
        foreach($championship->getClubsSortedByName() as $team){
            $injuryTab = $this->findOneBy(['day' => $day, 'club' => $team, 'season' => $championship->getSeason()]);
            if($injuryTab == null){
                $this->createInjuryTab($day, $team, $championship->getSeason());
            }
            $injuryTabs->add($this->getCurrentInjuryTab($team));
        }
        return $injuryTabs;
    }

    public function getCurrentInjuryTab(Club $team):InjuryTab{
        $currentDay = $team->getChampionship()->getCurrentDay();
        $injuryTab = $this->findOneBy(['club' => $team, 'day' => $currentDay, 'season' => $team->getChampionship()->getSeason()]);
        if($injuryTab==null){
            $injuryTab = $this->createInjuryTab($currentDay, $team, $team->getChampionship()->getSeason());
            $this->em->persist($injuryTab);
            $this->em->flush();
        }
        return $injuryTab;
    }

    public function getInjuryTabsFromChamp(Championship $champ, int $season, int $day):ArrayCollection
    {
        $clubs = [];
        if($season == $_ENV["ACTIVE_SEASON"])
        {
            $clubs = $champ->getClubsSortedByName();
        }
        else
        {
            $clubs = $this->clubRepository->getClubsFrom($champ->getSlug(), $season);
        }
        $injuryTabs = new ArrayCollection();
        foreach ($clubs as $club) {
            $injuryTab = $this->findOneBy(['club' => $club, 'day' => $day, 'season' => $champ->getSeason()]);
            if ($injuryTab == null) {
                $injuryTab = $this->createInjuryTab($day, $club, $season);
            }
            $injuryTabs->add($injuryTab);
        }
        return $injuryTabs;
    }


    public function createInjuryTab(int $day, Club $team, int $season): InjuryTab{
        $injuryTab = new InjuryTab($day, $team, $season);
            foreach($team->getPlayers() as $player) {
                if ($player->getInjuryStatus() != "OK") {
                    $injuryTab->addAbsent($player);
                }
            }
        $this->em->persist($injuryTab);
        $this->em->flush();
        return $injuryTab;
    }
//    /**
//     * @return InjuryTab[] Returns an array of InjuryTab objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InjuryTab
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
