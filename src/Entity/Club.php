<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


#[ORM\Entity(repositoryClass: ClubRepository::class)]
class Club
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Gedmo\Slug(fields: ["clubName"])]
    #[ORM\Column(length: 128, unique: true)]
    private $slug;

    #[ORM\Column(length: 255)]
    private ?string $cityName = null;

    #[ORM\OneToMany(mappedBy: 'club', targetEntity: Player::class)]
    private Collection $players;

    #[ORM\ManyToOne(inversedBy: 'clubs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Championship $championship = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $clubName = null;

    #[ORM\OneToMany(mappedBy: 'club', targetEntity: InjuryTab::class)]
    private Collection $injuryTabs;

    #[ORM\OneToMany(mappedBy: 'mentionned_club', targetEntity: Article::class)]
    private Collection $articles;

    #[ORM\OneToMany(mappedBy: 'associatedClub', targetEntity: Media::class)]
    private Collection $medias;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $lastInjuryUpdate = null;

    #[ORM\OneToMany(mappedBy: 'club', targetEntity: ExternalArticle::class)]
    private Collection $externalArticles;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $shortName = null;

    #[ORM\Column(nullable: true)]
    private ?int $activeSeason = null;

    #[ORM\OneToMany(mappedBy: 'left_club_instance', targetEntity: Signing::class)]
    private Collection $departures;

    #[ORM\OneToMany(mappedBy: 'joined_club_instance', targetEntity: Signing::class)]
    private Collection $arrivals;

    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'clubs')]
    private Collection $linkedArticles;

    #[ORM\Column]
    private ?bool $hasLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;
    /**
     * @return Collection
     */

    public function __construct($cityName = null, Championship $champ = null)
    {
        if ($cityName != null) {
            $this->championship = $champ;
        }
        if ($champ != null) {
            $this->cityName = $cityName;
        }
        $this->status = "not_updated";
        $this->lastInjuryUpdate = (new \DateTime());
        $this->players = new ArrayCollection();
        $this->injuryTabs = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->medias = new ArrayCollection();
        $this->externalArticles = new ArrayCollection();
        $this->arrivals = new ArrayCollection();
        $this->linkedArticles = new ArrayCollection();
        $this->summerContents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return string|null
     */
    public function getCityName(): ?string
    {
        return $this->cityName;
    }

    /**
     * @param string|null $cityName
     */
    public function setCityName(?string $cityName): void
    {
        $this->cityName = $cityName;
    }


    /**
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
            $player->setClub($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getClub() === $this) {
                $player->setClub(null);
            }
        }

        return $this;
    }

    public function getChampionship(): ?Championship
    {
        return $this->championship;
    }

    public function setChampionship(?Championship $championship): self
    {
        $this->championship = $championship;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getClubName(): ?string
    {
        return $this->clubName;
    }

    public function setClubName(?string $clubName): self
    {
        $this->clubName = $clubName;

        return $this;
    }

    public function __toString(): string
    {
        if ($this->clubName == null) {
            return $this->cityName;
        } else {
            return $this->getClubName();
        }
    }

    /**
     * @return Collection<int, InjuryTab>
     */
    public function getInjuryTabs(): Collection
    {
        return $this->injuryTabs;
    }

    public function addInjuryTab(InjuryTab $injuryTab): self
    {
        if (!$this->injuryTabs->contains($injuryTab)) {
            $this->injuryTabs->add($injuryTab);
            $injuryTab->setClub($this);
        }

        return $this;
    }

    public function removeInjuryTab(InjuryTab $injuryTab): self
    {
        if ($this->injuryTabs->removeElement($injuryTab)) {
            // set the owning side to null (unless already changed)
            if ($injuryTab->getClub() === $this) {
                $injuryTab->setClub(null);
            }
        }

        return $this;
    }

    public function getPlayersSortedByPosition(): Collection
    {
        $players = $this->getPlayers();
        $sortedPlayers = new ArrayCollection();
        foreach ($players as $player) {
            if ($player->getPosition() == 'GDB') {
                $sortedPlayers->add($player);
            }
        }
        foreach ($players as $player) {
            if ($player->getPosition() == 'DC') {
                $sortedPlayers->add($player);
            }
        }
        foreach ($players as $player) {
            if ($player->getPosition() == 'DL') {
                $sortedPlayers->add($player);
            }
        }
        foreach ($players as $player) {
            if ($player->getPosition() == 'MD') {
                $sortedPlayers->add($player);
            }
        }
        foreach ($players as $player) {
            if ($player->getPosition() == 'MO') {
                $sortedPlayers->add($player);
            }
        }
        foreach ($players as $player) {
            if ($player->getPosition() == 'A') {
                $sortedPlayers->add($player);
            }
        }
        return $sortedPlayers;
    }

    public function getClubNameToUpperCase(): string
    {
        return strtoupper($this->getClubName());
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setMentionnedClub($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getMentionnedClub() === $this) {
                $article->setMentionnedClub(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedia(Media $media): self
    {
        if (!$this->medias->contains($media)) {
            $this->medias->add($media);
            $media->setAssociatedClub($this);
        }

        return $this;
    }

    public function removeMedia(Media $media): self
    {
        if ($this->medias->removeElement($media)) {
            // set the owning side to null (unless already changed)
            if ($media->getAssociatedClub() === $this) {
                $media->setAssociatedClub(null);
            }
        }

        return $this;
    }

    public function getLastInjuryUpdate(): ?\DateTimeInterface
    {
        return $this->lastInjuryUpdate;
    }

    public function setLastInjuryUpdate(\DateTimeInterface $lastInjuryUpdate): self
    {
        $this->lastInjuryUpdate = $lastInjuryUpdate;

        return $this;
    }

    /**
     * @return Collection<int, ExternalArticle>
     */
    public function getExternalArticles(): Collection
    {
        return $this->externalArticles;
    }

    public function addExternalArticle(ExternalArticle $externalArticle): self
    {
        if (!$this->externalArticles->contains($externalArticle)) {
            $this->externalArticles->add($externalArticle);
            $externalArticle->setClub($this);
        }

        return $this;
    }

    public function removeExternalArticle(ExternalArticle $externalArticle): self
    {
        if ($this->externalArticles->removeElement($externalArticle)) {
            // set the owning side to null (unless already changed)
            if ($externalArticle->getClub() === $this) {
                $externalArticle->setClub(null);
            }
        }

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(?string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getActiveSeason(): ?int
    {
        return $this->activeSeason;
    }

    public function setActiveSeason(?int $activeSeason): self
    {
        $this->activeSeason = $activeSeason;

        return $this;
    }

    /**
     * @return Collection<int, Signing>
     */
    public function getArrivals(): Collection
    {
        return $this->arrivals;
    }

    public function addArrival(Signing $arrival): self
    {
        if (!$this->arrivals->contains($arrival)) {
            $this->arrivals->add($arrival);
            $arrival->setJoinedClubInstance($this);
        }

        return $this;
    }


    public function getDepartures(): Collection
    {
        return $this->departures;
    }

    /**
     * @param Collection $departures
     */
    public function setDepartures(Collection $departures): void
    {
        $this->departures = $departures;
    }

    public function addDeparture(Signing $departure):self
    {
        if(!$this->departures->contains($departure))
        {
            $this->departures->add($departure);
            $departure->setLeftClubInstance($this);
        }

        return $this;
    }
    public function removeArrival(Signing $arrival): self
    {
        if ($this->arrivals->removeElement($arrival)) {
            // set the owning side to null (unless already changed)
            if ($arrival->getJoinedClubInstance() === $this) {
                $arrival->setJoinedClubInstance(null);
            }
        }

        return $this;
    }

    public function getPlayersSortedByName(): array
    {
        $players = $this->getPlayers()->toArray();
        usort($players, function (Player $player1, Player $player2) {
            return strcmp($player1->getLastName(), $player2->getLastName());
        });
        return $players;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getLinkedArticles(): Collection
    {
        return $this->linkedArticles;
    }

    public function addLinkedArticle(Article $linkedArticle): self
    {
        if (!$this->linkedArticles->contains($linkedArticle)) {
            $this->linkedArticles->add($linkedArticle);
            $linkedArticle->addClub($this);
        }

        return $this;
    }

    public function removeLinkedArticle(Article $linkedArticle): self
    {
        if ($this->linkedArticles->removeElement($linkedArticle)) {
            $linkedArticle->removeClub($this);
        }

        return $this;
    }

    public function isHasLink(): ?bool
    {
        return $this->hasLink;
    }

    public function setHasLink(bool $hasLink): self
    {
        $this->hasLink = $hasLink;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }



}
