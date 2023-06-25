<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Championship;
use App\Entity\ExternalArticle;
use App\Entity\InjuryArticle;
use App\Form\ArticleType;
use App\Form\ExternalArticleType;
use App\Form\InjuryArticleType;
use App\InjuriesHandler\ArticleHandler;
use App\InjuriesHandler\InjuryArticleHandler;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\ChampionshipRepository;
use App\Repository\ClubRepository;
use App\Repository\InjuryArticleRepository;
use App\Repository\InjuryTabRepository;
use App\Repository\PlayerRepository;
use App\Repository\UserRepository;
use App\SEO\SeoResourcesHandler;
use Doctrine\ORM\EntityManagerInterface;
use Leogout\Bundle\SeoBundle\Provider\SeoGeneratorProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChampController extends AbstractController
{
    public function __construct(public EntityManagerInterface $em,
                                public InjuryArticleRepository $injuryArticleRepository,
                                public InjuryTabRepository $injuryTabRepository,
                                public ChampionshipRepository $championshipRepository,
                                public InjuryArticleHandler $injuryArticleHandler,
                                public ArticleRepository $articleRepository,
                                public ClubRepository $clubRepository,
                                public SeoResourcesHandler $seoResourcesHandler,
                                public SeoGeneratorProvider $seoGeneratorProvider,
                                public ArticleHandler $articleHandler,
                                public UserRepository $userRepository,
                                public CategoryRepository $categoryRepository
    )
    {
    }

    #[Route('/{slug}/home', name: 'app_championship_home')]
    public function home(Championship $champ){
        $home = 'champ/'.$champ->getSlug().'html.twig';
        $lastArticles = $this->articleRepository->getLastArticles(15, $champ);

        $groupClubs = $this->clubRepository->getClubsByStatus($champ, "group_ok");
        $confClubs = $this->clubRepository->getClubsByStatus($champ, "conf_ok");
        $otherClubs = $this->clubRepository->getClubsByStatus($champ, "not_updated");

        return $this->render('champ/index.html.twig', [
            'champ' => $champ,
            'last_articles' => $lastArticles,
            'group_ok_clubs' => $groupClubs,
            'conf_ok_clubs' => $confClubs,
            'not_updated_clubs' => $otherClubs
        ]);

    }

    #[Route('/article/{slug}/create', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Championship $champ, Request $req): Response
    {
        $connectedUser = $this->getUser();
        if($connectedUser == null || in_array("'ROLE_ADMIN'", $connectedUser->getRoles())){
            return $this->redirectToRoute('app_homepage');
        }

        $author = $this->userRepository->findOneBy(['email' => $connectedUser->getUserIdentifier()]);
        $article = new Article();
        $article->setAuthor($author);
        $article->setMentionedChamp($champ);

        $form = $this->createForm(ArticleType::class, $article, [
            'attr' => [
                'champ' => $champ
            ]
        ]);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->articleHandler->handleArticle($article);
            $this->em->persist($article);
            $this->em->flush();
            $this->addFlash('success', "L'article a bien été créé et sera publié ".$article->publishedAtToString());
            return $this->redirectToRoute('app_custom_homepage');
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/injury/article/{slug}/create', name: 'app_injury_article_create')]
    public function makeInjuryArticle(Championship $champ, Request $req): Response
    {
        $connectedUser = $this->getUser();
        if($connectedUser == null || in_array("'ROLE_ADMIN'", $connectedUser->getRoles())){
            return $this->redirectToRoute('app_homepage');
        }

        $author = $this->userRepository->findOneBy(['email' => $connectedUser->getUserIdentifier()]);

        $day = $champ -> getCurrentDay();
        $article = $this->injuryArticleRepository->findOneBy(['championship' => $champ, 'day'=> $day, 'season' => $champ->getSeason()]);

        if($article != null){
            $this->addFlash('danger', "L'article en ".$champ->getChampName()." de la J".$day." a déjà été créé");
            return $this->redirectToRoute('app_custom_homepage');
        }

        $article = new InjuryArticle($champ, $day);
        $article->setAuthor($author);

        $form = $this->createForm(InjuryArticleType::class, $article, [
            'attr' => [
                'champ' => $champ
            ]
        ]);

        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            if($this->injuryArticleHandler->handleArticle($article)){
                $this->addFlash('success', "L'article en ".$champ->getChampName()." de la J".$day." a été créé. Il sera publié le ".$article->getPublishedAt()->format('d/m')." à ".$article->getPublishedAt()->format('h')."h");
            }
            else{
                $this->addFlash('danger', "L'article en ".$champ->getChampName()." de la J".$day." a déjà été créé");
            }
            return $this->redirectToRoute('app_custom_homepage');
        }

        return $this->render('injury_article/create.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    #[Route('/ext/article/{slug}/create', name: 'app_external_article_create')]
    public function createExtArticle(Championship $champ, Request $req):Response
    {
        $connectedUser = $this->getUser();
        if($connectedUser == null || in_array("'ROLE_ADMIN'", $connectedUser->getRoles())){
            return $this->redirectToRoute('app_homepage');
        }

        $extArticle = new ExternalArticle($champ);

        $form = $this->createForm(ExternalArticleType::class, $extArticle, [
            'attr' => [
                'champ' => $champ
            ]
        ]);

        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($extArticle);
            $this->em->flush();
            $this->addFlash('success', "Le lien a été ajouté avec succès");
            return $this->redirectToRoute('app_custom_homepage');
        }

        return $this->render('external_article/create.html.twig', [
            'article' => $extArticle,
            'form' => $form->createView()
        ]);

    }
    #[Route('/injury/article/{slug}/{season}/J{day}', name: 'app_injury_article_show')]
    public function show(string $slug, int $season, int $day): Response
    {
        $champ = $this->championshipRepository->findOneBy(['slug' => $slug]);
        $article = $this->injuryArticleRepository->findOneBy(['championship' => $champ, 'day' => $day, 'season'=>$season]);
        if($article == null){
            return $this->redirectToRoute('app_homepage');
        }

        $groupClubs = $this->clubRepository->getClubsByStatus($champ,"group_ok");
        $confClubs = $this->clubRepository->getClubsByStatus($champ, "conf_ok");
        $otherClubs = $this->clubRepository->getClubsByStatus($champ, "not_updated");

        $basicResource = $this->seoResourcesHandler->createBasicResourcesForInjuryArticle($article);
        $twitterResource = $this->seoResourcesHandler->createTwitterResourcesForInjuryArticle($article);
        $ogResource = $this->seoResourcesHandler->createOgResourcesForInjuryArticle($article);

        $this->seoGeneratorProvider->get('basic')->fromResource($basicResource);
        $this->seoGeneratorProvider->get('twitter')->fromResource($twitterResource);
        $this->seoGeneratorProvider->get('og')->fromResource($ogResource);

        $injuryTabs = $this->injuryTabRepository->getInjuryTabsFromChamp($champ, $season, $article->getDay());
        return $this->render('injury_article/show.html.twig', [
            'article' => $article,
            'injuryTabs' => $injuryTabs,
            'modify' => false,
            'champ' => $champ,
            'group_ok_clubs' => $groupClubs,
            'conf_ok_clubs' => $confClubs,
            'not_updated_clubs' => $otherClubs,
        ]);
    }

    #[Route('/mercato/article/show/{slug}/{season}', name: 'app_mercato_article_show')]
    public function showMercatoArticle(string $slug, int $season):Response
    {
        $championship = $this->championshipRepository->findOneBy(['slug'=>$slug]);
        $category = $this->categoryRepository->findOneBy(['slug'=>'mercato']);
        $article = $this->articleRepository->findOneBy(['mentioned_champ' => $championship, 'category' => $category]);
        if($article == null){
            return $this->redirectToRoute('app_homepage');
        }

        $lastArticles = $this->articleRepository->getLastArticles(15, $championship);

        $basicResource = $this->seoResourcesHandler->createBasicResourcesForArticle($article);
        $twitterResource = $this->seoResourcesHandler->createTwitterResourcesArticle($article);
        $ogResource = $this->seoResourcesHandler->createOgResourcesArticle($article);

        $this->seoGeneratorProvider->get('basic')->fromResource($basicResource);
        $this->seoGeneratorProvider->get('twitter')->fromResource($twitterResource);
        $this->seoGeneratorProvider->get('og')->fromResource($ogResource);

        return $this->render('mercato/mercato_article.html.twig', [
            'article' => $article,
            'clubs' => $championship->getClubsSortedByName(),
            'last_articles' => $lastArticles
        ]);
    }

}
