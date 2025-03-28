<?php

namespace App\Entity;

use App\Repository\ChampionshipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


#[ORM\Entity(repositoryClass: ChampionshipRepository::class)]
class Championship
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Gedmo\Slug(fields:["champ_name"])]
    #[ORM\Column(length: 128, unique: true)]
    private $slug;

    #[ORM\Column(length: 255)]
    private ?string $champ_name = null;

    #[ORM\OneToMany(mappedBy: 'championship', targetEntity: Club::class)]
    private Collection $clubs;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $currentDay = null;

    #[ORM\OneToMany(mappedBy: 'championship', targetEntity: InjuryArticle::class)]
    private Collection $articles;

    #[ORM\OneToMany(mappedBy: 'associatedChampionship', targetEntity: Media::class)]
    private Collection $medias;

    #[ORM\OneToMany(mappedBy: 'mentioned_champ', targetEntity: Article::class)]
    private Collection $commonArticles;

    #[ORM\OneToMany(mappedBy: 'championship', targetEntity: ExternalArticle::class)]
    private Collection $externalArticles;

    #[ORM\Column(nullable: true)]
    private ?int $season = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    public function __construct($champ_name)
    {
        $this->champ_name = $champ_name;
        $this->currentDay = 1;
        $this->clubs = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->medias = new ArrayCollection();
        $this->commonArticles = new ArrayCollection();
        $this->externalArticles = new ArrayCollection();
    }

    /**
     * @return int|null
     */
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

    public function setSlug(string $slug):void
    {
        $this->slug = $slug;
    }

    public function getChampName(): ?string
    {
        return $this->champ_name;
    }

    public function setChampName(string $champ_name): self
    {
        $this->champ_name = $champ_name;

        return $this;
    }

    /**
     * @return Collection<int, Club>
     */
    public function getClubs(): Collection
    {
        return $this->clubs;
    }

    public function addClub(Club $club): self
    {
        if (!$this->clubs->contains($club)) {
            $this->clubs->add($club);
            $club->setChampionship($this);
        }

        return $this;
    }

    public function removeClub(Club $club): self
    {
        if ($this->clubs->removeElement($club)) {
            // set the owning side to null (unless already changed)
            if ($club->getChampionship() === $this) {
                $club->setChampionship(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->champ_name;
    }

    public function getCurrentDay(): ?int
    {
        return $this->currentDay;
    }

    public function setCurrentDay(int $currentDay): self
    {
        $this->currentDay = $currentDay;

        return $this;
    }

    /**
     * @return Collection<int, InjuryArticle>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(InjuryArticle $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setChampionship($this);
        }

        return $this;
    }

    public function removeArticle(InjuryArticle $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getChampionship() === $this) {
                $article->setChampionship(null);
            }
        }

        return $this;
    }

    public function getClubsSortedByName(): array
    {
        $clubs = $this->getClubs()->toArray();
        usort($clubs, function (Club $club1, Club $club2) {
            return strcmp($club1->getCityName(), $club2->getCityName());
        });
        return $clubs;
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
            $media->setAssociatedChampionship($this);
        }

        return $this;
    }

    public function removeMedia(Media $media): self
    {
        if ($this->medias->removeElement($media)) {
            // set the owning side to null (unless already changed)
            if ($media->getAssociatedChampionship() === $this) {
                $media->setAssociatedChampionship(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getCommonArticles(): Collection
    {
        return $this->commonArticles;
    }

    public function addCommonArticle(Article $commonArticle): self
    {
        if (!$this->commonArticles->contains($commonArticle)) {
            $this->commonArticles->add($commonArticle);
            $commonArticle->setMentionedChamp($this);
        }

        return $this;
    }

    public function removeCommonArticle(Article $commonArticle): self
    {
        if ($this->commonArticles->removeElement($commonArticle)) {
            // set the owning side to null (unless already changed)
            if ($commonArticle->getMentionedChamp() === $this) {
                $commonArticle->setMentionedChamp(null);
            }
        }

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
            $externalArticle->setChampionship($this);
        }

        return $this;
    }

    public function removeExternalArticle(ExternalArticle $externalArticle): self
    {
        if ($this->externalArticles->removeElement($externalArticle)) {
            // set the owning side to null (unless already changed)
            if ($externalArticle->getChampionship() === $this) {
                $externalArticle->setChampionship(null);
            }
        }

        return $this;
    }

    public function getSeason(): ?int
    {
        return $this->season;
    }

    public function setSeason(?int $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
