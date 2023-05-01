<?php

namespace App\Controller;

use App\Entity\Championship;
use App\InjuriesHandler\ClubHandler;
use App\InjuriesHandler\InjuryTabHandler;
use App\Repository\ArticleRepository;
use App\Repository\ChampionshipRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomController extends AbstractController
{
    public function __construct(public ChampionshipRepository $championshipRepository,
                                public InjuryTabHandler $injuryTabHandler,
                                public ArticleRepository $articleRepository,
    public ClubHandler $clubHandler)
    {
    }

    #[Route('/custom/homepage', name: 'app_custom_homepage')]
    public function homepage():Response
    {
        $allChamps = $this->championshipRepository->findChamps();

        return $this->render('custom/homepage.html.twig', [
            'controller_name' => 'HomepageController',
            'champs' => $allChamps
        ]);
    }

    #[Route('/{slug}/next/day', name: 'app_next_day')]
    public function addOneDay(Championship $champ): Response
    {
        $connectedUser = $this->getUser();
        if($connectedUser == null || in_array("'ROLE_ADMIN'", $connectedUser->getRoles())){
            return $this->redirectToRoute('app_homepage');
        }

        if ($champ->getCurrentDay() >= 38) {
            $this->addFlash('danger', "La journée 39 est impossible !");
        } else {
            $this->championshipRepository->setNextDay($champ);
            $this->injuryTabHandler->handleChamp($champ);
            $this->clubHandler->reinisializeUpdates($champ);
            $this->addFlash('success', 'Prochaine journée : J'.$champ->getCurrentDay());
        }
        return $this->redirectToRoute('app_custom_homepage');
    }
}