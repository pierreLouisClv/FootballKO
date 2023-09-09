<?php

namespace App\InjuriesHandler;

use App\Entity\Championship;
use App\Entity\Club;
use App\Entity\InjuryArticle;
use App\Repository\CategoryRepository;
use App\Repository\InjuryArticleRepository;
use App\Repository\InjuryTabRepository;
use Doctrine\ORM\EntityManagerInterface;

class InjuryArticleHandler{

    public function __construct(public InjuryTabRepository $injuryTabRepository,
                                public CategoryRepository $categoryRepository,
                                public EntityManagerInterface $em,
                                public InjuryArticleRepository $injuryArticleRepository
    )
    {
    }

    public function handleArticle(InjuryArticle $article):bool{
        $day = $article->getDay();
        $champ = $article->getChampionship();
        $injuryArticle = $this->injuryArticleRepository->findBy(['day' => $day, 'championship' => $champ, 'season' => $champ->getSeason()]);
        if(count($injuryArticle) > 1){
            return false;
        }
        $article->setCategory($this->categoryRepository->findOneBy(['name' => 'Tableau']));
        $this->em->persist($article);
        $this->em->flush();
        return true;
    }

    public function updateArticle(Championship $champ):void{
        $currentDay = $champ -> getCurrentDay();
        if($injuryArticle = $this->injuryArticleRepository->findOneBy(["day" => $currentDay, "championship" => $champ, 'season' => $champ])){
            $injuryArticle -> setUpdatedAt((new \DateTime()));
            $this->em->flush();
        }
    }
}