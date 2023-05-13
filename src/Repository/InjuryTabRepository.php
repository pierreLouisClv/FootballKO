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
        foreach($championship->getClubs() as $team){
            $injuryTab = $this->findOneBy(['day' => $day, 'club' => $team]);
            if($injuryTab == null){
                $this->createInjuryTab($day, $team);
            }
            $injuryTabs->add($this->getCurrentInjuryTab($team));
        }
        return $injuryTabs;
    }

    public function getCurrentInjuryTab(Club $team):InjuryTab{
        $currentDay = $team->getChampionship()->getCurrentDay();
        $injuryTab = $this->findOneBy(['club' => $team, 'day' => $currentDay]);
        if($injuryTab==null){
            $injuryTab = $this->createInjuryTab($currentDay, $team);
            $this->em->persist($injuryTab);
            $this->em->flush();
        }
        return $injuryTab;
    }

    public function getInjuryTabsFromChamp(Championship $champ, $day):ArrayCollection
    {
        $clubs = $this->clubRepository->getClubsSortedByCityName($champ);
        $injuryTabs = new ArrayCollection();
        foreach ($clubs as $club) {
            $injuryTab = $this->findOneBy(['club' => $club, 'day' => $day]);
            if ($injuryTab == null) {
                $injuryTab = $this->createInjuryTab($day, $club);
            }
            $injuryTabs->add($injuryTab);
        }
        return $injuryTabs;
    }


    public function createInjuryTab($day, Club $team): InjuryTab{
        $injuryTab = new InjuryTab($day, $team);
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
