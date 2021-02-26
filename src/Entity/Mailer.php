<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MailerRepository")
 */
class Mailer implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $userFrom;

    /**
     * @ORM\Column(type="text")
     */
    private $usersTo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sentAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Backpack", inversedBy="mailers")
     */
    private $backpack;


    public function __construct()
    {
        $this->setSentAt(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserFrom(): ?string
    {
        return $this->userFrom;
    }

    public function setUserFrom(string $userFrom): self
    {
        $this->userFrom = $userFrom;

        return $this;
    }

    public function getUsersTo(): ?string
    {
        return $this->usersTo;
    }

    public function setUsersTo(string $usersTo): self
    {
        $this->usersTo = $usersTo;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

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

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeInterface $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
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

}
