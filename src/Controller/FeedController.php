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
        $category = $this->categoryRepository->findOneBy(['slug' => 'mercato']);
        $mercatoArticles = $this->articleRepository->getMercatoTabArticles($activeChamps, $category);
        $removedArticlesSlug = new ArrayCollection();

        $lastArticles = new ArrayCollection($this->articleRepository->getLastArticles());
        $articles = new ArrayCollection();

        foreach ($lastArticles as $article)
        {
            if (!($mercatoArticles->contains($article)))
            {
                $articles->add($article);
            }

        }


        return $this->render('feed.xml.twig', [
            'injury_articles' => $injuryArticles,
            'mercato_articles' => $mercatoArticles,
            'last_articles' => $articles
        ]);
    }
}