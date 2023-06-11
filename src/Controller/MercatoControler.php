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

class MercatoControler extends AbstractController
{

    public function __construct(public InjuryArticleRepository $injuryArticleRepository,
                                public ChampionshipRepository  $championshipRepository,
                                public ArticleRepository       $articleRepository)
    {
    }

    #[Route('/mercato/{limit}', name: 'app_mercato')]
    public function index(int $limit = 15): Response
    {
        $lastArticles = $this->articleRepository->getMercatoArticles($limit);
        return $this->render('mercato/index.html.twig', [
            'last_articles' => $lastArticles,
            'limit' => $limit
        ]);
    }
}