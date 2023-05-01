<?php

namespace App\InjuriesHandler;

use App\Entity\Article;
use App\Entity\Championship;
use App\Entity\Club;
use App\Entity\InjuryArticle;
use App\Repository\CategoryRepository;
use App\Repository\InjuryArticleRepository;
use App\Repository\InjuryTabRepository;
use Doctrine\ORM\EntityManagerInterface;

class ArticleHandler{

    public function __construct(public InjuryTabRepository $injuryTabRepository,
                                public CategoryRepository $categoryRepository,
                                public EntityManagerInterface $em,
                                public InjuryArticleRepository $injuryArticleRepository
    )
    {
    }

    public function handleArticle(Article $article):bool{
        $array = explode("\n", $article->getKeywords());
        $article->setKeywords(implode(";", $array));
        $this->em->persist($article);
        $this->em->flush();
        return true;
    }
}