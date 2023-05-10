<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Player;
use App\Form\AddPlayerType;
use App\Form\UpdatePlayerType;
use App\InjuriesHandler\InjuryArticleHandler;
use App\InjuriesHandler\InjuryTabHandler;
use App\InjuriesHandler\UpdatePlayerFormHandler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController
{
    public function __construct(public EntityManagerInterface $em,
                                public UpdatePlayerFormHandler $updatePlayerFormHandler,
                                public InjuryTabHandler $injuryTabHandler,
                                public InjuryArticleHandler $injuryArticleHandler)
    {
    }

    #[Route('/player/{id}/delete', name:'app_player_delete')]
    public function deletePlayer(Player $player):Response{
        $connectedUser = $this->getUser();
        if($connectedUser == null || in_array("'ROLE_ADMIN'", $connectedUser->getRoles())){
            return $this->redirectToRoute('app_homepage');
        }

        $team = $player->getClub();
        $playerName = $player->getFullName();

        $this->em->remove($player);
        $this->em->flush();

        $this->addFlash('success', $playerName." a bien été supprimé.");
        return $this->redirectToRoute('app_team_modify', ['slug' => $team->getSlug()]);
    }

    #[Route('/player/{slug}/add', name : 'app_player_new')]
    public function addPlayer(Club $team, Request $req):Response{
        $connectedUser = $this->getUser();
        if($connectedUser == null || in_array("'ROLE_ADMIN'", $connectedUser->getRoles())){
            return $this->redirectToRoute('app_homepage');
        }

        $player = new Player();
        $player->setClub($team);

        $form = $this->createForm(UpdatePlayerType::class, $player, [
            'attr' => [
                'valMin' => $player->getClub()->getChampionship()->getCurrentDay(),
                'valMax' => 38,
                'club' => $team,
                'create' => true
            ]
        ]);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($player);
            $this->updatePlayerFormHandler -> removeInjuriesIfPlayerIsNotInjured($player);
            $this->injuryTabHandler -> handlePlayer($player);
            $this->injuryArticleHandler -> updateArticle($player -> getClub() -> getChampionship());

            $this->em->flush();

            $this->addFlash('success', $player->getFullName()." a bien été ajouté.");
            return $this->redirectToRoute('app_team_modify', ['slug' => $player->getClub()->getSlug()]);
        }

        return $this->render('player/add.html.twig', [
            'form' => $form->createView(),
            'team' => $team
        ]);
    }

    #[Route('/player/{id}/update', name : 'app_player_update')]
    public function updatePlayer(Player $player, Request $req):Response{
        $connectedUser = $this->getUser();
        if($connectedUser == null || in_array("'ROLE_ADMIN'", $connectedUser->getRoles())){
            return $this->redirectToRoute('app_homepage');
        }

        $club = $player -> getClub();
        $champ = $club -> getChampionship();

        $form = $this->createForm(UpdatePlayerType::class, $player, [
            'attr' => [
            'valMin' => $champ->getCurrentDay() + 1,
            'valMax' => 38,
            'club' => $club,
                'create' => false
            ]
        ]);

        $form->handleRequest($req);

        if($form->isSubmitted()){
            $this->updatePlayerFormHandler -> removeInjuriesIfPlayerIsNotInjured($player);
            $this->injuryTabHandler -> handlePlayer($player);
            $this->injuryArticleHandler -> updateArticle($player -> getClub() ->getChampionship());

            $this->em->flush();

            $this->addFlash('success', $player->getFullName()." a bien été mis à jour.");
            return $this->redirectToRoute('app_team_modify', ['slug' => $player->getClub()->getSlug()]);
        }

        return $this->render('player/update.html.twig', [
            'player' => $player,
            'form' => $form->createView()
        ]);
    }
}
