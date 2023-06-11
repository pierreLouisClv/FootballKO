<?php

namespace App\Repository;

use App\Entity\Championship;
use App\Entity\Club;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Championship>
 *
 * @method Championship|null find($id, $lockMode = null, $lockVersion = null)
 * @method Championship|null findOneBy(array $criteria, array $orderBy = null)
 * @method Championship[]    findAll()
 * @method Championship[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChampionshipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,
                                public EntityManagerInterface $em,
    public ClubRepository $clubRepository
    )
    {
        parent::__construct($registry, Championship::class);
    }

    public function save(Championship $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Championship $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function setNextDay(Championship $champ):void{
        $prevDay = $champ->getCurrentDay();
        $champ->setCurrentDay($prevDay + 1);
        $this->em->flush();
    }

    public function findActiveChamps():ArrayCollection
    {
        $champs = new ArrayCollection();
        $ligue1 = $this->findOneBy(['slug' => 'ligue-1', 'isActive' => true]);
        $premierleague = $this->findOneBy(['slug' => 'premier-league', 'isActive' => true]);
        $seriea = $this->findOneBy(['slug' => 'serie-a', 'isActive' => true]);
        $liga = $this->findOneBy(['slug' => 'laliga', 'isActive' => true]);
        $bundesliga = $this->findOneBy(['slug' => 'bundesliga', 'isActive' => true]);

        if($ligue1 != null)
        {
            $champs->add($ligue1);
        }
        if($premierleague != null)
        {
            $champs->add($premierleague);
        }
        if($seriea != null)
        {
            $champs->add($seriea);
        }
        if($liga != null)
        {
            $champs->add($liga);
        }
        if($bundesliga != null)
        {
            $champs->add($bundesliga);
        }


        return $champs;
    }

    public function findChampsFromSeason(int $season):ArrayCollection
    {
        $champs = new ArrayCollection();
        $ligue1 = $this->findOneBy(['champ_name' => 'Ligue 1', 'season' => $season]);
        $premierleague = $this->findOneBy(['champ_name' => 'Premier League', 'season' => $season]);
        $seriea = $this->findOneBy(['champ_name' => 'Série A', 'season' => $season]);
        $liga = $this->findOneBy(['champ_name' => 'LaLiga', 'season' => $season]);
        $bundesliga = $this->findOneBy(['champ_name' => 'Bundesliga', 'season' => $season]);

        if($ligue1 != null)
        {
            $champs->add($ligue1);
        }
        if($premierleague != null)
        {
            $champs->add($premierleague);
        }
        if($seriea != null)
        {
            $champs->add($seriea);
        }
        if($liga != null)
        {
            $champs->add($liga);
        }
        if($bundesliga != null)
        {
            $champs->add($bundesliga);
        }


        return $champs;
    }

    public function copyChampsForNextSeason(ArrayCollection $lastSeasonChamps, array $relegatedClubsId):array
    {
        $champs = new ArrayCollection();
        foreach($lastSeasonChamps as $lastSeasonChamp)
        {
            $lastSeason = $lastSeasonChamp->getSeason();
            $originalSlug = $lastSeasonChamp->getSlug();
            $lastSeasonChamp->setSlug($originalSlug."-".$lastSeason);
            $lastSeasonChamp->setIsActive(false);

            $champ = new Championship($lastSeasonChamp->getChampName());
            $champ->setSeason($lastSeason + 1);
            $champ->setCurrentDay(0);
            $champ->setSlug($originalSlug);
            $champ->setIsActive(true);
            $this->copyArticlesAndMediasFromChamp($champ, $lastSeasonChamp);
            $this->copyClubsFromChamp($champ, $lastSeasonChamp, $relegatedClubsId);
            $this->em->persist($champ);
            $champs->add($champ);
        }
        return $champs->toArray();
    }

    public function copyArticlesAndMediasFromChamp(Championship $newChamp, Championship $copiedChamp):void
    {
        foreach ($copiedChamp->getCommonArticles() as $article)
        {
            $newChamp->addCommonArticle($article);
        }
        foreach ($copiedChamp->getMedias() as $media)
        {
            $newChamp->addMedia($media);
        }
        foreach ($copiedChamp->getExternalArticles() as $extArticle)
        {
            $newChamp->addExternalArticle($extArticle);
        }
    }

    public function copyClubsFromChamp(Championship $newChamp, Championship $copiedChamp, array $relegatedClubsId):void
    {
        $notCopiedClubs = new ArrayCollection($relegatedClubsId);
        foreach ($copiedChamp->getClubs() as $club)
        {
            $isRelegated = false;
            foreach ($notCopiedClubs as $id)
            {
                if($club->getId() == $id)
                {
                    $isRelegated = true;
                }
            }
            if(!$isRelegated)
            {
                $newChamp->addClub($club);
            }
            $club->setActiveSeason($newChamp->getSeason());
            $this->em->persist($club);
        }
    }

    public function findPreviousChampionship(Championship $championship):Championship
    {
        $season = $championship->getSeason() - 1;
        $name = $championship->getChampName();
        return $this->findOneBy(['champ_name'=>$name, 'season' => $season]);
    }
}
