<?php

namespace App\Controller;

use App\Entity\Championship;
use App\Entity\InjuryArticle;
use App\Entity\Player;
use App\Form\AddPlayerType;
use App\Form\InjuryArticleType;
use App\Repository\ChampionshipRepository;
use App\Repository\InjuryArticleRepository;
use App\Repository\InjuryTabRepository;
use App\SEO\SeoResourcesHandler;
use Doctrine\ORM\EntityManagerInterface;
use Leogout\Bundle\SeoBundle\Provider\SeoGeneratorProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InjuryArticleController extends AbstractController
{
    public function __construct(
        public InjuryArticleRepository $injuryArticleRepository,
        public ChampionshipRepository $championshipRepository,
    )
    {
    }

    #[Route('/injury/article/{slug}', name: 'app_injury_article')]
    public function show(Championship $champ):Response
    {
        $day = $champ->getCurrentDay();
        $injuryArticle = $this->injuryArticleRepository->findOneBy(['championship'=>$champ, 'day'=>$day]);
        $now = new \DateTime();
        while($injuryArticle == null || $injuryArticle->getPublishedAt() > $now){
            $day = $day - 1;
            if($day == 0){
                return $this->redirectToRoute('app_homepage');
            }
        }

        return $this->redirectToRoute('app_injury_article_show', ['slug'=>$champ->getSlug(), 'day'=>$day]);
    }

}
