<?php

namespace WebEtDesign\RgpdBundle\Event;


use App\Entity\User\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserExportData extends Event
{
    public const NAME = 'USER_EXPORT_DATA';

    private User $user;

    private ?string $data = '';

    /**
     * UserExportData constructor.
     * @param User $user
     * @param string|null $data
     */
    public function __construct(User $user, ?string $data)
    {
        $this->user = $user;
        $this->data = $data;
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
     * @return string|null
     */
    public function getData(bool $toArray = false)
    {
        $data = json_decode($this->data, true);

        if(isset($data['_archive'])){
            unset($data['_archive']);
        }

        return $toArray ? $data : json_encode($data);
    }

    public function getDataArray(){
        return $this->getData(true);
    }

    public function getArchiveLink(){
        $data = json_decode($this->data, true);

        if(isset($data['_archive'])){
            return $data['_archive'];
        }
        return null;
    }

    /**
     * @param string|null $data
     */
    public function setData(?string $data): void
    {
        $this->data = $data;
    }

    public function getLocale(){
        return $this->getUser()->getLocale();
    }
}
