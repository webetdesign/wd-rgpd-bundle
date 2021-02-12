<?php


namespace WebEtDesign\RgpdBundle\Event;


use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class RoutineChangeOldPasswordReminder extends Event
{
    public const NAME = 'ROUTINE_CHANGE_OLD_PASSWORD_REMINDER';

    private User $user;

    private ?string $resetLink;

    private string $useTime;

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
    public function getResetLink(): ?string
    {
        return $this->resetLink;
    }

    /**
     * @param ?string $resetLink
     */
    public function setResetLink(?string $resetLink): void
    {
        $this->resetLink = $resetLink;
    }

    /**
     * @return string
     */
    public function getUseTime(): string
    {
        return $this->useTime;
    }

    /**
     * @param string $useTime
     */
    public function setUseTime(string $useTime): void
    {
        $this->useTime = $useTime;
    }
}
