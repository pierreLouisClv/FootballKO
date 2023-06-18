<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Championship;
use App\Entity\Club;
use App\Entity\InjuryArticle;
use App\Form\ArticleType;
use App\InjuriesHandler\ContentArticleHandler;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\ChampionshipRepository;
use App\Repository\InjuryArticleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MercatoControler extends AbstractController
{

    public function __construct(public InjuryArticleRepository $injuryArticleRepository,
                                public ChampionshipRepository  $championshipRepository,
                                public ArticleRepository       $articleRepository,
    public UserRepository $userRepository,
    public ContentArticleHandler $contentArticleHandler,
    public CategoryRepository $categoryRepository,
    public EntityManagerInterface $entityManager)
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

    #[Route('/mercato/article/add', name:'app_mercato_article')]
    public function add(Request $request):Response
    {
        $connectedUser = $this->getUser();
        if($connectedUser == null || in_array("'ROLE_ADMIN'", $connectedUser->getRoles())){
            return $this->redirectToRoute('app_homepage');
        }

        $author = $this->userRepository->findOneBy(['email' => $connectedUser->getUserIdentifier()]);
        $article = new Article();
        $article->setAuthor($author);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        $cat = $this->categoryRepository->findOneBy(['slug' => 'mercato']);

        if($form->isSubmitted() && $form->isValid()) {
            $this->contentArticleHandler->handleContent($article);
            $this->entityManager->persist($article);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_custom_homepage');
        }
        return $this->render('mercato/add_article.html.twig', [
            'form' => $form->createView()]);
    }

    #[Route('/mercato/table/{slug}/read', name:'app_mercato_table')]
    public function showClubTable(Club $club):Response
    {
        $arrivals = $club->getArrivals();
        $departures = $club->getDepartures();

        return $this->render('mercato/club_table.html.twig', [
            'arrivals' => $arrivals,
            'departures' => $departures
        ]);
    }


}