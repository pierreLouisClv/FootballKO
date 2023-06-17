<?php

namespace App\Entity;

use App\Repository\SigningRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SigningRepository::class)]
class Signing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'departures')]
    private ?Club $left_club_instance = null;

    #[ORM\ManyToOne(inversedBy: 'arrivals')]
    private ?Club $joined_club_instance = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?int $transfer_amount = null;

    #[ORM\Column]
    private ?int $season = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $left_club = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $joined_club = null;

    #[ORM\ManyToOne(inversedBy: 'signs')]
    private ?Player $player_instance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $player = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJoinedClubInstance(): ?Club
    {
        return $this->joined_club_instance;
    }

    public function setJoinedClubInstance(?Club $joined_club_instance): self
    {
        $this->joined_club_instance = $joined_club_instance;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTransferAmount(): ?int
    {
        return $this->transfer_amount;
    }

    public function setTransferAmount(?int $transfer_amount): self
    {
        $this->transfer_amount = $transfer_amount;

        return $this;
    }

    public function getSeason(): ?int
    {
        return $this->season;
    }

    public function setSeason(int $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getLeftClub(): ?string
    {
        return $this->left_club;
    }

    public function setLeftClub(?string $left_club): self
    {
        $this->left_club = $left_club;

        return $this;
    }

    public function getJoinedClub(): ?string
    {
        return $this->joined_club;
    }

    public function setJoinedClub(?string $joined_club): self
    {
        $this->joined_club = $joined_club;

        return $this;
    }

    /**
     * @return Club|null
     */
    public function getLeftClubInstance(): ?Club
    {
        return $this->left_club_instance;
    }

    /**
     * @param Club|null $left_club_instance
     */
    public function setLeftClubInstance(?Club $left_club_instance): void
    {
        $this->left_club_instance = $left_club_instance;
    }


    public function getPlayerInstance(): ?Player
    {
        return $this->player_instance;
    }

    public function setPlayerInstance(?Player $player_instance): self
    {
        $this->player_instance = $player_instance;

        return $this;
    }

    public function getPlayer(): ?string
    {
        return $this->player;
    }

    public function setPlayer(?string $player): self
    {
        $this->player = $player;

        return $this;
    }
}
