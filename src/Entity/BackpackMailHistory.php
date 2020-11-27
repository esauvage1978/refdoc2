<?php

namespace App\Entity;

use App\Repository\BackpackMailHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BackpackMailHistoryRepository::class)
 */
class BackpackMailHistory implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Backpack::class, inversedBy="backpackMailHistories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $backpack;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sendAt;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    public function __construct() {
        $this->getSendAt(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBackpack(): ?Backpack
    {
        return $this->backpack;
    }

    public function setBackpack(?Backpack $backpack): self
    {
        $this->backpack = $backpack;

        return $this;
    }

    public function getSendAt(): ?\DateTimeInterface
    {
        return $this->sendAt;
    }

    public function setSendAt(\DateTimeInterface $sendAt): self
    {
        $this->sendAt = $sendAt;

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
}
