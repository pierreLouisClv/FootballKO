<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Player;
use App\Entity\Signing;
use App\Repository\ChampionshipRepository;
use App\Repository\ClubRepository;
use App\Repository\PlayerRepository;
use App\Repository\SigningRepository;
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
        public EntityManagerInterface $entityManager,
        public SigningRepository $signingRepository
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
        return $this->render('signing/add_signing.html.twig',
            [
                'championships' => $champs,
                'modify' => false,
                'id' => 0,
                'signing' => new Signing()
        ]);
    }

    #[Route('signing/update/{id}', name: 'app_signing_update')]
    public function updateSigning(Signing $signing)
    {
        $connectedUser = $this->getUser();
        if($connectedUser == null || in_array("'ROLE_ADMIN'", $connectedUser->getRoles())){
            return $this->redirectToRoute('app_homepage');
        }

        $champs = $lastSeasonChamps = $this->championshipRepository->findActiveChamps();
        return $this->render('signing/add_signing.html.twig',
            [
                'championships' => $champs,
                'modify' => true,
                'signing' => $signing,
                'id' => $signing->getId()
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

    #[Route('signing/handle/{id}', name:'app_signing_handle')]
    public function handleSigningAdding(Request $request, int $id)
    {
        if($id == 0)
        {
            $signing = new Signing();
        }
        else
        {
            $signing = $this->signingRepository->findOneBy(['id' => $id]);
        }

        if($id == 0)
        {
            //PLAYER
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
        }

        // LEFT CLUB
        $leftClubSlug = $request->request->get('club_left');
        $isClubLeft = $request->request->get('is_club_left');
        if($isClubLeft == "true")
        {
            $signing->setLeftClub(null);
            $signing->setLeftClubInstance(null);
        }
        else if($leftClubSlug != null && $leftClubSlug != "")
        {
            $club = $this->clubRepository->findOneBy(['slug' => $leftClubSlug]);
            if($signing->getLeftClubInstance() !== $club)
            {
                $signing->setLeftClubInstance($club);
            }
        }
        else
        {
            $clubName = $request->request->get('left_club_name');
            if($signing->getLeftClub() != $clubName)
            {
                $signing->setLeftClub($clubName);
            }
        }

        //JOINED CLUB
        $joinedClubSlug = $request->request->get('club_joined');
        $isClubJoined = $request->request->get('is_club_joined');
        if($isClubJoined == "true")
        {
            $signing->setLeftClubInstance(null);
            $signing->setLeftClub(null);
        }
        else if($joinedClubSlug != null && $joinedClubSlug != "")
        {
            $club = $this->clubRepository->findOneBy(['slug' => $joinedClubSlug]);
            if($signing->getJoinedClub() !== $club)
            {
                $signing->setJoinedClubInstance($club);
                if($id == 0)
                {
                    $player->setClub($club);
                }
                else
                {
                    $signing->getPlayerInstance()->setClub($club);
                }
            }

        }
        else
        {
            $clubName = $request->request->get('joined_club_name');
            if($signing->getJoinedClub() != $clubName)
            {
                $signing->setJoinedClub($clubName);
            }
        }

        //TYPE
        $type = $request->request->get('type');
        if($signing->getType() != $type)
        {
            $signing->setType($type);
        }

        //AMOUNT
        $amount = $request->request->get('amount');
        if($amount != null && $amount != "")
        {
            if($signing->getTransferAmount() != $amount)
            {
                $signing->setTransferAmount($amount);
            }
        }
        if($id == 0)
        {
            $signing->setSeason(2023);
            $this->entityManager->persist($signing);
        }
        $this->entityManager->flush();

        return $this->redirectToRoute('app_custom_homepage');
    }
}