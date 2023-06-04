<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Eko\FeedBundle\Feed\FeedManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedController extends AbstractController {

    public function __construct(public FeedManager $feedManager,
                                public ArticleRepository $articleRepository)
    {
    }

    #[Route('/feed', name:'app_feed')]
    public function feed():Response
    {
        $articles = $this->articleRepository->findAll();

        $feed = $this->feedManager->get('article');
        $feed->addFromArray($articles);

        return new Response($feed->render('rss'));
    }
}