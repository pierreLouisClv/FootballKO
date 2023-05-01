<?php

namespace App\Controller;

use App\Entity\Championship;
use App\Entity\Club;
use App\Entity\InjuryTab;
use App\InjuriesHandler\ClubHandler;
use App\InjuriesHandler\InjuryTabHandler;
use App\Repository\ChampionshipRepository;
use App\Repository\InjuryArticleRepository;
use App\Repository\InjuryTabRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InjuryTabController extends AbstractController
{
    public function __construct(public InjuryTabRepository $injuryTabRepository,
                                public InjuryTabHandler $injuryTabHandler,
                                public InjuryArticleRepository $injuryArticleRepository,
                                public ClubHandler $clubHandler)
    {
    }

    #[Route('/injury/{slug}/show', name: 'app_injury_tab')]
    public function show(Championship $championship):Response{
        $injuryTabs = $this->injuryTabRepository -> getCurrentInjuryTabs($championship);
        return $this->render('injury_tab/read.html.twig', [
            'modify' => false,
            'injuryTabs' => $injuryTabs,
            'championship' => $championship,
            'controller_name' => 'InjuryTabController',
        ]);
    }

    #[Route('/injury/{slug}/modify', name: 'app_injury_tab_modify')]
    public function modify(Championship $championship):Response{
        $connectedUser = $this->getUser();
        if($connectedUser == null || in_array("'ROLE_ADMIN'", $connectedUser->getRoles())){
            return $this->redirectToRoute('app_homepage');
        }

        $injuryTabs = $this->injuryTabRepository -> getCurrentInjuryTabs($championship);
        $injuryArticle = $this->injuryArticleRepository->findOneBy(['championship' => $championship, 'day'=>$championship->getCurrentDay()]);

        return $this->render('injury_tab/champ/modify.html.twig', [
            'modify' => true,
            'injuryTabs' => $injuryTabs,
            'injuryArticle' => $injuryArticle,
            'championship' => $championship,
            'controller_name' => 'InjuryTabController',
        ]);
    }

    #[Route('/injury/{slug}/update/conf', name: 'app_update_conf')]
    public function updateConf(Club $team):Response{
        $connectedUser = $this->getUser();
        if($connectedUser == null || in_array("'ROLE_ADMIN'", $connectedUser->getRoles())){
            return $this->redirectToRoute('app_homepage');
        }

        $injuryTab = $this->injuryTabRepository->getCurrentInjuryTab($team);
        $this->injuryTabHandler->updateInjuryTabStatus($injuryTab, "conf_ok");
        $this->clubHandler->updateTeamStatus($team, "conf_ok");

        $this->addFlash('success', "Conférence de presse mise à jour.");
        return $this->redirectToRoute('app_team_modify', ['slug' => $team->getSlug()]);
    }

    #[Route('/injury/{slug}/update/group', name: 'app_update_group')]
    public function updateGroup(Club $team):Response{
        $connectedUser = $this->getUser();
        if($connectedUser == null || in_array("'ROLE_ADMIN'", $connectedUser->getRoles())){
            return $this->redirectToRoute('app_homepage');
        }
        $injuryTab = $this->injuryTabRepository->getCurrentInjuryTab($team);
        $this->injuryTabHandler->updateInjuryTabStatus($injuryTab, "group_ok");
        $this->clubHandler->updateTeamStatus($team, "group_ok");

        $this->addFlash('success', "Groupe mis à jour à jour.");
        return $this->redirectToRoute('app_team_modify', ['slug' => $team->getSlug()]);
    }

}
