<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\ChampionshipRepository;
use App\Repository\InjuryArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Eko\FeedBundle\Feed\FeedManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedController extends AbstractController {

    public function __construct(public FeedManager $feedManager,
                                public ArticleRepository $articleRepository,
                                public ChampionshipRepository $championshipRepository,
                                public InjuryArticleRepository $injuryArticleRepository,
                                public CategoryRepository $categoryRepository)
    {
    }

    #[Route('/feed', name:'app_feed')]
    public function feed():Response
    {
        $champs = $this->championshipRepository->findChampsFromSeason(2023);
        $activeChamps = $this->championshipRepository->findActiveChamps();
        /*$champs = $this->championshipRepository->findActiveChamps();*/

        $injuryArticles = $this->injuryArticleRepository->getLastInjuryArticles($champs);

        $lastArticles = new ArrayCollection($this->articleRepository->getLastArticles());
        $articles = new ArrayCollection();

        foreach ($lastArticles as $article)
        {
            $articles->add($article);

        }


        return $this->render('feed.xml.twig', [
            'injury_articles' => $injuryArticles,
            'last_articles' => $articles
        ]);
    }

    #[Route('/sitemap', name:'app_sitemap')]
    public function siteMap():Response
    {
        $champs = $this->championshipRepository->findChampsFromSeason(2023);
        $activeChamps = $this->championshipRepository->findActiveChamps();
        /*$champs = $this->championshipRepository->findActiveChamps();*/

        $injuryArticles = $this->injuryArticleRepository->getLastInjuryArticles($champs);

        $lastArticles = new ArrayCollection($this->articleRepository->getLastArticles());
        $articles = new ArrayCollection();

        foreach ($lastArticles as $article)
        {
            $articles->add($article);

        }


        return $this->render('sitemap.xml.twig', [
            'injury_articles' => $injuryArticles,
            'last_articles' => $articles
        ]);
    }
}