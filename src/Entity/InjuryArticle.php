<?php

namespace App\Entity;

use App\Repository\InjuryArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


#[ORM\Entity(repositoryClass: InjuryArticleRepository::class)]
class InjuryArticle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Gedmo\Slug(fields:["title", "id"])]
    #[ORM\Column(length: 128, unique: true)]
    private $slug;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $introduction = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Championship $championship = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $day = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: InjuryTab::class)]
    private Collection $injuryTabs;

    #[ORM\ManyToOne(inversedBy: 'injuryArticles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Media $media = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $publishedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'injuryArticles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'injuryArticles')]
    private ?User $author = null;


    public function __construct(Championship $champ, $day)
    {
        $this->setCreatedAt((new \DateTimeImmutable())->modify('+2 hours'));
        $this->setUpdatedAt((new \DateTime())->modify('+2 hours'));
        $this->setChampionship($champ);
        $this->setDay($day);
        $this->injuryTabs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(?string $introduction): self
    {
        $this->introduction = $introduction;

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

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(int $day): self
    {
        $this->day = $day;

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
            $injuryTab->setArticle($this);
        }

        return $this;
    }
    public function removeInjuryTab(InjuryTab $injuryTab): self
    {
        if ($this->injuryTabs->removeElement($injuryTab)) {
            // set the owning side to null (unless already changed)
            if ($injuryTab->getArticle() === $this) {
                $injuryTab->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable|null $createdAt
     */
    public function setCreatedAt(?\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getPublishedAt(): ?\DateTime
    {
        return $this->publishedAt;
    }

    /**
     * @param \DateTime|null $publishedAt
     */
    public function setPublishedAt(?\DateTime $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }



    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function makeIntroduction(): void
    {
        if($this->introduction == null){
            $array_content = explode("<strong>", $this->content);
            $this->introduction = $array_content[1];
        }
    }

    public function publishedAtToString():string{
        return $this->publishedAt->format('d/m/y')." à ".$this->publishedAt->format('H:i');
    }

    public function updatedAtToString():string{
        return $this->updatedAt->format('d/m/y')." à ".$this->updatedAt->format('H:i');
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }


}
