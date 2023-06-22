<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\ChampionshipRepository;
use App\Repository\InjuryArticleRepository;
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
        $champs = $this->championshipRepository->findChampsFromSeason(2022);
        $activeChamps = $this->championshipRepository->findActiveChamps();
        /*$champs = $this->championshipRepository->findActiveChamps();*/
        $injuryArticles = $this->injuryArticleRepository->getLastInjuryArticles($champs);
        $lastArticles = $this->articleRepository->getLastArticles();
        $category = $this->categoryRepository->findOneBy(['slug' => 'mercato']);
        $mercatoArticles = $this->articleRepository->getMercatoTabArticles($activeChamps, $category);

        return $this->render('feed.txt.twig', [
            'injury_articles' => $injuryArticles,
            'mercato_articles' => $mercatoArticles,
            'last_articles' => $lastArticles
        ]);
    }
}