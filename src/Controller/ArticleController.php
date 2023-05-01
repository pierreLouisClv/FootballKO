<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Championship;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\SEO\BasicArticleResources;
use App\SEO\SeoResourcesHandler;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Leogout\Bundle\SeoBundle\Provider\SeoGeneratorProvider;

#[Route('/article')]
class ArticleController extends AbstractController
{

    public function __construct(
        public EntityManagerInterface $em,
        public ArticleRepository $articleRepository,
        public SeoResourcesHandler $seoResourcesHandler,
        public SeoGeneratorProvider $seoGeneratorProvider)
    {

    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route('/{slug}', name: 'app_article_show', methods: ['GET', 'POST'])]
    public function show(Article $article): Response
    {
        $basicResource = $this->seoResourcesHandler->createBasicResourcesForArticle($article);
        $twitterResource = $this->seoResourcesHandler->createTwitterResourcesArticle($article);
        $ogResource = $this->seoResourcesHandler->createOgResourcesArticle($article);

        $this->seoGeneratorProvider->get('basic')->fromResource($basicResource);
        $this->seoGeneratorProvider->get('twitter')->fromResource($twitterResource);
        $this->seoGeneratorProvider->get('og')->fromResource($ogResource);

        $lastArticles = new ArrayCollection($this->articleRepository->getLastArticles());
        $lastArticles->removeElement($article);

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'modify' => false,
            'last_articles' => $lastArticles
        ]);
    }
}
