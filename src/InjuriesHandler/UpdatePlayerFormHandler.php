<?php

namespace App\InjuriesHandler;
use App\Entity\Championship;
use App\Entity\Club;
use App\Entity\Player;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class UpdatePlayerFormHandler{

    public function __construct(public EntityManagerInterface $em,
                                public InjuryTabHandler $injuryTabHandler,
                                public ArticleRepository $articleRepository)
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

    public function setDefaultInfo(Player $player, Championship $champ, Club $club):void
    {
        $info = $this->articleRepository->createQueryBuilder('article')
            ->andWhere('article.mentioned_champ = :champ')
            ->setParameter(':champ', $champ)
            ->andWhere('article.mentionned_club is NULL')
            ->orWhere('article.mentionned_club = :club')
            ->setParameter(':club', $club)
            ->orderBy('article.publishedAt', 'DESC')
            ->getQuery()
            ->getResult();
        if(count($info)>0){
            $player->setInfo($info[0]);
        }
    }
}



