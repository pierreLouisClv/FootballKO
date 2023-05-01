<?php

namespace App\Controller;

use App\Entity\Championship;
use App\Entity\InjuryArticle;
use App\Repository\ArticleRepository;
use App\Repository\ChampionshipRepository;
use App\Repository\InjuryArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{

    public function __construct(public InjuryArticleRepository $injuryArticleRepository,
                                public ChampionshipRepository $championshipRepository,
                                public ArticleRepository $articleRepository)
    {
    }

    #[Route('/homepage', name: 'app_homepage')]
    public function index(): Response
    {
        $champs = $this->championshipRepository->findChamps();
        $injuryArticles = $this->injuryArticleRepository->getLastInjuryArticles($champs);
        $lastArticles = $this->articleRepository->getLastArticles();
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'articles' => $injuryArticles,
            'last_articles' => $lastArticles
        ]);
    }
}
