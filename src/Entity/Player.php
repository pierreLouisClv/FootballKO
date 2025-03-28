<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $last_name = null;

    #[ORM\ManyToMany(targetEntity: Article::class, inversedBy: 'mentionedPlayers')]
    private Collection $mentions;

    #[ORM\Column(length: 255)]
    private ?string $injury_status = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $injury_type = null;

    #[ORM\ManyToOne(inversedBy: 'players')]
    private ?Club $club = null;

    #[ORM\ManyToMany(targetEntity: InjuryTab::class, mappedBy: 'absent')]
    private Collection $injuryTabs;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $position = null;

    #[ORM\Column(nullable: true)]
    private ?int $day_return = null;

    #[ORM\ManyToOne]
    private ?Article $info = null;

    #[ORM\Column(nullable: true)]
    private ?bool $date_of_return_is_exact = null;

    #[ORM\ManyToOne(inversedBy: 'players')]
    private ?ExternalArticle $external_info = null;

    #[ORM\OneToMany(mappedBy: 'player_instance', targetEntity: Signing::class)]
    private Collection $signs;

    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'players')]
    private Collection $articles;

    public function __construct($firstName=null, $lastName=null, $position=null, Club $club=null)
    {
        if($firstName!=null){
            $this->first_name = $firstName;
        }
        if($lastName!=null){
            $this->last_name = $lastName;
        }
        if($position!=null){
            $this->position = $position;
        }
        if($club!=null){
            $this->club = $club;
        }
        $this->injury_status = "injured";
        $this->mentions = new ArrayCollection();
        $this->injuryTabs = new ArrayCollection();
        $this->incertainTabs = new ArrayCollection();
        $this->signs = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getMentions(): Collection
    {
        return $this->mentions;
    }

    public function addMention(Article $mention): self
    {
        if (!$this->mentions->contains($mention)) {
            $this->mentions->add($mention);
        }

        return $this;
    }

    public function removeMention(Article $mention): self
    {
        $this->mentions->removeElement($mention);

        return $this;
    }


    public function getInjuryStatus(): ?string
    {
        return $this->injury_status;
    }

    public function setInjuryStatus(string $injury_status): self
    {
        $this->injury_status = $injury_status;

        return $this;
    }

    public function getInjuryType(): ?string
    {
        return $this->injury_type;
    }

    public function setInjuryType(?string $injury_type): self
    {
        $this->injury_type = $injury_type;

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

        return $this;
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
            $injuryTab->addAbsent($this);
        }

        return $this;
    }

    public function removeInjuryTab(InjuryTab $injuryTab): self
    {
        if ($this->injuryTabs->removeElement($injuryTab)) {
            $injuryTab->removeAbsent($this);
        }

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getFullName(): string{
        $firstName = $this->getFirstName() ? $this->getFirstName() : "";
        $lastName = $this->getLastName() ? $this->getLastName() : "";
        return $firstName." ".$lastName;
    }

    public function downtimeToString():string{
        $mounths = $this->downtime->format("%m");
        $days = $this->downtime->format("%d");
        if($mounths == 0 or $mounths==null){
            return $days." jours";
        }
        elseif($days == 0 or $days==null){
            return $mounths." mois";
        }
        return $mounths." mois et ".$days." jours";
    }

    public function getLabelForInjuryStatus():string{
        return match ($this->getInjuryStatus()) {
            'available' => 'Disponible',
            'incertain' => 'Incertain',
            'injured' => 'Blessé',
            'out_of_group' => 'Hors-Groupe',
            default => '?',
        };

    }

    public function getDayReturn(): ?int
    {
        return $this->day_return;
    }

    public function setDayReturn(?int $day_return): self
    {
        $this->day_return = $day_return;

        return $this;
    }

    public function getInfo(): ?Article
    {
        return $this->info;
    }

    public function setInfo(?Article $info): self
    {
        $this->info = $info;

        return $this;
    }

    public function isDateOfReturnIsExact(): ?bool
    {
        return $this->date_of_return_is_exact;
    }

    public function setDateOfReturnIsExact(?bool $date_of_return_is_exact): self
    {
        $this->date_of_return_is_exact = $date_of_return_is_exact;

        return $this;
    }

    public function __toString(): string
    {
        $lastName = $this->last_name;
        $firstName = $this->first_name;
        return $lastName == null ? $firstName : $lastName;
    }

    public function getExternalInfo(): ?ExternalArticle
    {
        return $this->external_info;
    }

    public function setExternalInfo(?ExternalArticle $external_info): self
    {
        $this->external_info = $external_info;

        return $this;
    }

    /**
     * @return Collection<int, Signing>
     */
    public function getSignings(): Collection
    {
        return $this->signings;
    }

    public function addSigning(Signing $signing): self
    {
        if (!$this->signings->contains($signing)) {
            $this->signings->add($signing);
            $signing->setPlayer($this);
        }

        return $this;
    }

    public function removeSigning(Signing $signing): self
    {
        if ($this->signings->removeElement($signing)) {
            // set the owning side to null (unless already changed)
            if ($signing->getPlayer() === $this) {
                $signing->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Signing>
     */
    public function getSigns(): Collection
    {
        return $this->signs;
    }

    public function addSign(Signing $sign): self
    {
        if (!$this->signs->contains($sign)) {
            $this->signs->add($sign);
            $sign->setPlayerInstance($this);
        }

        return $this;
    }

    public function removeSign(Signing $sign): self
    {
        if ($this->signs->removeElement($sign)) {
            // set the owning side to null (unless already changed)
            if ($sign->getPlayerInstance() === $this) {
                $sign->setPlayerInstance(null);
            }
        }

        return $this;
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
            $article->addPlayer($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            $article->removePlayer($this);
        }

        return $this;
    }
}
