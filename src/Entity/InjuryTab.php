<?php

namespace App\Entity;

use App\Repository\InjuryTabRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InjuryTabRepository::class)]
class InjuryTab
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updateAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'injuryTabs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Club $club = null;

    #[ORM\ManyToMany(targetEntity: Player::class, inversedBy: 'injuryTabs')]
    private Collection $absent;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $day = null;

    #[ORM\ManyToOne(inversedBy: 'injuryTabs')]
    private ?InjuryArticle $article = null;

    public function __construct($day, Club $club)
    {
        $this->club = $club;
        $this->day = $day;
        $this->createdAt = new \DateTimeImmutable();
        $this->updateAt = new \DateTime();
        $this->status = "not_updated";
        $this->absent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

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
     * @return Collection<int, Player>
     */
    public function getAbsent(): Collection
    {
        return $this->absent;
    }

    public function addAbsent(Player $absent): self
    {
        if (!$this->absent->contains($absent)) {
            $this->absent->add($absent);
        }

        return $this;
    }

    public function removeAbsent(Player $absent): self
    {
        $this->absent->removeElement($absent);

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

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(int $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getArticle(): ?InjuryArticle
    {
        return $this->article;
    }

    public function setArticle(?InjuryArticle $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getUpdatedAtToString():string{
        $updatedAt = $this->updateAt;
        return $updatedAt->format('d/m/y H:i');
    }

}
