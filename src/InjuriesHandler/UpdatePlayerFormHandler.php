<?php

namespace App\InjuriesHandler;
use App\Entity\Player;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class UpdatePlayerFormHandler{

    public function __construct(public EntityManagerInterface $em, public InjuryTabHandler $injuryTabHandler)
    {
    }

    public function isMatchingRequirements(Player $player):bool
    {
        if ($player->getInjuryStatus() == "OK") {
            return true;
        }
        return true;
    }

    public function removeInjuriesIfPlayerIsNotInjured(Player $player):void{
        if($player->getInjuryStatus() == "OK"){
            $player->setDayReturn(null);
            $player->setDayReturn(null);
            $player->setInjuryType(null);
        }
        elseif($player->getInjuryStatus() == "uncertain"){
            $player->setDayReturn(null);
            $player->setDateOfReturnIsExact(null);
        }
        elseif($player->getInjuryStatus() == "out_of_group" || $player->getInjuryStatus() == "exclude" || $player->getInjuryStatus() == "suspended" || $player->getInjuryStatus() == "sick"){
            $player->setInjuryType(null);
        }
            if($player->getDayReturn() == null){
                $player->setDateOfReturnIsExact(null);
            }


        $this->em->flush();
    }
}



