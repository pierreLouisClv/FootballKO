<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
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

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etiquette = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $introduction = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToMany(targetEntity: Player::class, mappedBy: 'mentions')]
    private Collection $mentionedPlayers;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $publishedAt = null;

    #[ORM\ManyToOne(inversedBy: 'associatedArticles')]
    private ?Media $media = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?Club $mentionned_club = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?User $author = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $keywords = null;

    public function __construct()
    {
        $this->createdAt = (new \DateTimeImmutable())->modify('+2 hours');
        $this->mentionedPlayers = new ArrayCollection();

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

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getEtiquette(): ?string
    {
        return $this->etiquette;
    }

    public function setEtiquette(?string $etiquette): self
    {
        $this->etiquette = $etiquette;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getMentionedPlayers(): Collection
    {
        return $this->mentionedPlayers;
    }

    /**
     * @param Collection $mentionedPlayers
     */
    public function setMentionedPlayers(Collection $mentionedPlayers): void
    {
        $this->mentionedPlayers = $mentionedPlayers;
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
     * @return \DateTimeImmutable|null
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

    public function getDateInterval() :string{
        $dateInterval = $this->publishedAt->diff((new \DateTime())->modify('+2 hours'));

        if ($dateInterval->days > 0) {
            return "il y a ".$dateInterval->format('%a jours');
        } elseif ($dateInterval->h > 0) {
            return "il y a ".$dateInterval->format('%h heures');
        } elseif ($dateInterval->i > 0) {
            return "il y a ".$dateInterval->format('%i minutes');
        } else {
            return "il y a ".$dateInterval->format('%s secondes');
        }
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

    public function getShortIntroduction():string
    {
        if (strlen($this->introduction) > 100) {
            return substr($this->introduction, 0, 150) . "...";
        }
        return $this->introduction;
    }

    public function makeIntroduction(): void
    {
        if($this->introduction == null){
            $array_content = explode("<strong>", $this->content);
            $array_content_2 = explode("</stong>", $array_content[1]);
            $this->introduction = $array_content_2[0];
        }
    }

    public function getDDMM(): string{
        return $this->publishedAt->format('d/m');
    }

    public function getHTMLContent():string{
        return strip_tags($this->content);
    }

    public function publishedAtToString():string{
        return $this->publishedAt->format('d/m/y')." à ".$this->publishedAt->format('H:i');
    }

    public function getMentionnedClub(): ?Club
    {
        return $this->mentionned_club;
    }

    public function setMentionnedClub(?Club $mentionned_club): self
    {
        $this->mentionned_club = $mentionned_club;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function getSeparatedKeywords(): string
    {
        $keywords = $this->keywords;
        $array = explode('\n', $keywords);
        return implode(', ', $array);
    }

    public function setKeywords(?string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }



}
