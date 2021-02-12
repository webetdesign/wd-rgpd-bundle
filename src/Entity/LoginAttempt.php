<?php

namespace WebEtDesign\RgpdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="WebEtDesign\RgpdBundle\Repository\LoginAttemptRepository")
 * @ORM\Table(name="user__login_attempt")
 */
class LoginAttempt
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $ipAddress;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $firewall;

    public function __construct(?string $ipAddress, ?string $username, ?string $firewall)
    {
        $this->ipAddress = $ipAddress;
        $this->username = $username;
        $this->firewall = $firewall;
        $this->date = new \DateTimeImmutable('now');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getFirewall(): ?string
    {
        return $this->firewall;
    }

    public function setIpAddress(?string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function setFirewall(?string $firewall): self
    {
        $this->firewall = $firewall;

        return $this;
    }
}
