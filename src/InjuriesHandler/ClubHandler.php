<?php

namespace App\InjuriesHandler;

use App\Entity\Championship;
use App\Entity\Club;
use App\Repository\ChampionshipRepository;
use App\Repository\ClubRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ClubHandler{

    public function __construct(
        public ChampionshipRepository $championshipRepository,
        public EntityManagerInterface $em
    )
    {
    }

    public function updateClub(Club $team):void
    {
        $team->setLastInjuryUpdate(new DateTime());
        $this->em->flush();
    }

    public function updateTeamStatus(Club $team, string $status):void{
        $team->setStatus($status);
        $team->setLastInjuryUpdate(new DateTime());
        $this->em->flush();
    }
    public function reinisializeUpdates(Championship $championship):void
    {
        foreach($championship->getClubs() as $club){
            $club->setStatus('not_updated');
        }
        $this->em->flush();
    }

}