<?php

namespace App\InjuriesHandler;
use App\Entity\Championship;
use App\Entity\Club;
use App\Entity\InjuryTab;
use App\Entity\Player;
use App\Repository\InjuryTabRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class InjuryTabHandler{

    public function __construct(public EntityManagerInterface $em,
                                public InjuryTabRepository $injuryTabRepository,
                                public ClubHandler $clubHandler,
                                public InjuryArticleHandler $injuryArticleHandler)
    {
    }

    public function updateInjuryTabStatus(InjuryTab $injuryTab, string $status):void{
        $injuryTab->setStatus($status);
        $this->em->flush();
    }
    public function updateInjuryTab(Club $team):void{
        $day = $team->getChampionship()->getCurrentDay();

        $injuryTab = $this->injuryTabRepository -> findOneBy(['club' => $team, 'day' => $day, 'season' => $team->getChampionship()->getSeason()]);
        if($injuryTab == null){
            $injuryTab = $this->injuryTabRepository->createInjuryTab($day, $team, $team->getChampionship()->getSeason());
            $this->em->persist($injuryTab);
            $this->em->flush();
        }

        $injuryTab -> setUpdateAt((new \DateTime()));
        $this->em->flush();

    }

    public function handlePlayer(Player $player):void{
        $team = $player -> getClub();
        $day = $team -> getChampionship() -> getCurrentDay();
        $injuryTab = $this->injuryTabRepository -> findOneBy(['day' => $day, 'club' => $team, 'season' => $team -> getChampionship() -> getSeason()]);

        if($injuryTab == null){
            $injuryTab = $this->injuryTabRepository->createInjuryTab($day, $team, $team->getChampionship()->getSeason());
            $this->em->persist($injuryTab);
            $this->em->flush();
        }

        if($player->getInjuryStatus() == 'OK'){
            if($injuryTab->getAbsent()->contains($player)){
                $injuryTab->removeAbsent($player);
            }
        }
        else{
            if(!$injuryTab->getAbsent()->contains($player)){
                $injuryTab->addAbsent($player);
            }
        }
        $this->updateInjuryTab($team);
        $this->injuryArticleHandler->updateArticle($team->getChampionship());
        $this->clubHandler->updateClub($team);
        $this->em->flush();
    }

    public function handleChamp(Championship $champ)
    {
        foreach($champ->getClubs() as $club){
            $injuryTab = $this->injuryTabRepository->findOneBy(['club' => $club, 'day' => $champ->getCurrentDay(), 'season' => $champ->getSeason()]);
            if($injuryTab == null){
                $injuryTab = $this->injuryTabRepository->createInjuryTab($champ->getCurrentDay(), $club, $champ->getSeason());
                $this->em->persist($injuryTab);
            }
            $this->em->flush();
        }
    }

}

