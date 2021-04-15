<?php


namespace WebEtDesign\RgpdBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class RoutineInactivityNotification extends Event
{
    public const NAME = 'ROUTINE_INACTIVITY_NOTIFICATION';

    private $user;

    private ?string $ctoLink = null;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getEmail() : ?string {
        return $this->user->getEmail();
    }

    /**
     * @return string
     */
    public function getCtoLink(): ?string
    {
        return $this->ctoLink;
    }

    /**
     * @param ?string $ctoLink
     */
    public function setCtoLink(?string $ctoLink): void
    {
        $this->ctoLink = $ctoLink;
    }
}
