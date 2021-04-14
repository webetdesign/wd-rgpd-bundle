<?php

namespace WebEtDesign\RgpdBundle\Event;


use App\Entity\User\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserExportData extends Event
{
    public const NAME = 'USER_EXPORT_DATA';

    private User $user;

    private string $json;

    /**
     * UserExportData constructor.
     * @param User $user
     * @param string $json
     */
    public function __construct(User $user, string $json)
    {
        $this->user = $user;
        $this->json = $json;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getJson(): string
    {
        return $this->json;
    }

    /**
     * @param string $json
     */
    public function setJson(string $json): void
    {
        $this->json = $json;
    }

    public function getLocale(){
        return $this->getUser()->getLocale();
    }
}
