<?php


namespace WebEtDesign\RgpdBundle\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

trait RgpdAnonymizeFields
{
    /**
     * @var DateTimeInterface|null $anonymizedAt
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected ?DateTimeInterface $anonymizedAt = null;

    /**
     * @return DateTimeInterface|null
     */
    public function getAnonymizedAt(): ?DateTimeInterface
    {
        return $this->anonymizedAt;
    }

    /**
     * @param DateTimeInterface|null $anonymizedAt
     */
    public function setAnonymizedAt(?DateTimeInterface $anonymizedAt): void
    {
        $this->anonymizedAt = $anonymizedAt;
    }


    public function isAnonyme(){
        return $this->getAnonymizedAt() !== null;
    }
}
