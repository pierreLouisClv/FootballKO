<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Player;
use App\Entity\Signing;
use App\Repository\ChampionshipRepository;
use App\Repository\ClubRepository;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SigningController extends AbstractController {
    public function __construct(
        public ChampionshipRepository $championshipRepository,
        public ClubRepository $clubRepository,
        public PlayerRepository $playerRepository,
        public EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('signing/add', name: 'app_signing_create')]
    public function addSigning():Response
    {
        $connectedUser = $this->getUser();
        if($connectedUser == null || in_array("'ROLE_ADMIN'", $connectedUser->getRoles())){
            return $this->redirectToRoute('app_homepage');
        }

        $champs = $lastSeasonChamps = $this->championshipRepository->findActiveChamps();
        return $this->render('form/add_signing.html.twig',
            [
                'championships' => $champs
            ]);
    }

    #[Route('signing/add/championship_selected', name:'app_signing_champ_selected')]
    public function showClubs(Request $request)
    {
        $championshipSlug = $request->query->get('championshipSlug');
        $champ = $this->championshipRepository->findOneBy(['slug'=>$championshipSlug]);
        $clubs = $champ->getClubsSortedByName();
        $htmlOptions = '<option value="">Sélectionne un club</option>';
        foreach ($clubs as $club) {
            $htmlOptions .= '<option value="' . $club->getSlug() . '">' . $club->getClubName() . '</option>';
        }

        return new Response($htmlOptions);
    }

    #[Route('signing/add/club_selected', name:'app_signing_club_selected')]
    public function showPlayers(Request $request)
    {
        $clubSlug = $request->query->get('clubSlug');
        $club = $this->clubRepository->findOneBy(['slug'=>$clubSlug]);
        $players = $club->getPlayersSortedByName();
        $htmlOptions = '<option value="">Sélectionne un joueur</option>';
        foreach ($players as $player) {
            $htmlOptions .= '<option value="' . $player->getId() . '">' . $player->getFullName() . '</option>';
        }

        return new Response($htmlOptions);
    }

    #[Route('signing/handle', name:'app_signing_handle')]
    public function handleSigning(Request $request)
    {
        $signing = new Signing();

        // PLAYER
        $playerId = $request->request->get('player');
        $player = new Player();
        if($playerId!=null && $playerId != "")
        {
            $player = $this->playerRepository->findOneBy(['id' => $playerId]);
        }
        else
        {
            $firstName = $request->request->get('player_first_name');
            $firstName == null ? : $player->setFirstName($firstName);
            $lastName = $request->request->get('player_last_name');
            $lastName == null ? : $player->setLastName($lastName);
            $this->entityManager->persist($player);
        }
        $signing->setPlayerInstance($player);

        // LEFT CLUB
        $leftClubSlug = $request->request->get('club_left');
        $isClubLeft = $request->request->get('is_club_left');
        if($isClubLeft == "true")
        {

        }
        else if($leftClubSlug != null && $leftClubSlug != "")
        {
            $club = $this->clubRepository->findOneBy(['slug' => $leftClubSlug]);
            $signing->setLeftClubInstance($club);
        }
        else
        {
            $clubName = $request->request->get('left_club_name');
            $signing->setLeftClub($clubName);
        }

        //JOINED CLUB
        $joinedClubSlug = $request->request->get('club_joined');
        $isClubJoined = $request->request->get('is_club_joined');
        if($isClubJoined == "true")
        {
        }
        else if($joinedClubSlug != null && $joinedClubSlug != "")
        {
            $club = $this->clubRepository->findOneBy(['slug' => $joinedClubSlug]);
            $signing->setJoinedClubInstance($club);
            $player->setClub($club);
        }
        else
        {
            $clubName = $request->request->get('joined_club_name');
            $signing->setJoinedClub($clubName);
        }

        //TYPE
        $type = $request->request->get('type');
        $signing->setType($type);

        //AMOUNT
        $amount = $request->request->get('amount');
        if($amount != null && $amount != "")
        {
            $signing->setTransferAmount($amount);
        }
        $signing->setSeason(2023);
        $this->entityManager->persist($signing);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_custom_homepage');
    }
}