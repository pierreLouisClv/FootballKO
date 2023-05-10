<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\InjuryArticle;
use App\Form\AddClubType;
use App\Form\InjuryArticleType;
use App\Repository\ClubRepository;
use App\Repository\InjuryArticleRepository;
use App\Repository\InjuryTabRepository;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    public function __construct(public ClubRepository $clubRepository,
                                public InjuryArticleRepository $injuryArticleRepository,
                                public InjuryTabRepository $injuryTabRepository,
                                public EntityManagerInterface $em,
                                public PlayerRepository $playerRepository
    )
    {
    }

    #[Route('/team', name: 'app_team')]
    public function index(Request $req): Response
    {
        $connectedUser = $this->getUser();
        if($connectedUser == null || in_array("'ROLE_ADMIN'", $connectedUser->getRoles())){
            return $this->redirectToRoute('app_homepage');
        }

        $team = new Club();

        $form = $this->createForm(AddClubType::class, $team);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($team);
            $this->em->flush();
            $this->addFlash('success', "Le club ".$team->getClubName()." a bien été créé.");
            return $this->redirectToRoute('app_custom_homepage');
        }
            return $this->render('team/create.html.twig', [
                'form' => $form->createView()
        ]);
    }

    #[Route('/team/{slug}', name: 'app_team_read')]
    public function read(Club $team): Response
    {
        $players = $this->playerRepository->getPlayersFromClubSortedByName($team);
        return $this->render('team/read.html.twig', [
            'team' => strtoupper($team->getClubName()),
            'players' => $players,
            'controller_name' => 'TeamController',
        ]);
    }

    #[Route('/team/{slug}/modify', name: 'app_team_modify')]
    public function modify(Club $team): Response
    {
        $connectedUser = $this->getUser();
        if($connectedUser == null || in_array("'ROLE_ADMIN'", $connectedUser->getRoles())){
            return $this->redirectToRoute('app_homepage');
        }

        $players = $this->playerRepository->getPlayersFromClubSortedByName($team);
        $champ = $team->getChampionship();
        $day = $champ->getCurrentDay();
        $injuryArticle = $this->injuryArticleRepository->findOneBy(['championship' => $champ, 'day'=>$day]);
        $injuryTab = $this->injuryTabRepository->findOneBy(['club' => $team, 'day' => $day]);
        return $this->render('team/modify.html.twig', [
            'team' => $team,
            'players' => $players,
            'injuryArticle' => $injuryArticle,
            'injuryTab' => $injuryTab,
            'controller_name' => 'TeamController',
        ]);
    }
}
