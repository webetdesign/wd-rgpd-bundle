<?php


namespace WebEtDesign\RgpdBundle\Event;


use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class RoutineInactivityNotification extends Event
{
    public const NAME = 'ROUTINE_INACTIVITY_NOTIFICATION';

    private User $user;

    private ?string $ctoLink = null;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
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
