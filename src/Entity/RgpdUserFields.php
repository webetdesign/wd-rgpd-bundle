<?php


namespace WebEtDesign\RgpdBundle\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use WebEtDesign\RgpdBundle\Validator\Constraints as WDConstraints;


trait RgpdUserFields
{

    /**
     * @WDConstraints\PasswordStrength(minLength=6, minStrength=4, groups={"Registration", "Profile", "ResetPassword", "ChangePassword"})
     */
    protected $plainPassword;

    /**
     * @var DateTimeInterface $lastUpdatePassword
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     * @Gedmo\Timestampable(on="change", field={"password"})
     */
    protected DateTimeInterface $lastUpdatePassword;

    /**
     * @var ?DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected ?DateTime $notifyUpdatePasswordAt;

    /**
     * @var ?DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected ?DateTime $notifyInactivityAt;


    public function getLastUpdatePassword(): DateTimeInterface
    {
        return $this->lastUpdatePassword;
    }

    public function setLastUpdatePassword(DateTimeInterface $lastUpdatePassword): self
    {
        $this->lastUpdatePassword = $lastUpdatePassword;

        return $this;
    }

    /**
     * @return ?DateTime
     */
    public function getNotifyUpdatePasswordAt(): ?DateTime
    {
        return $this->notifyUpdatePasswordAt;
    }

    /**
     * @param ?DateTime $notifyUpdatePasswordAt
     * @return self
     */
    public function setNotifyUpdatePasswordAt(?DateTime $notifyUpdatePasswordAt): self
    {
        $this->notifyUpdatePasswordAt = $notifyUpdatePasswordAt;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getNotifyInactivityAt(): ?DateTime
    {
        return $this->notifyInactivityAt;
    }

    /**
     * @param DateTime|null $notifyInactivityAt
     * @return self
     */
    public function setNotifyInactivityAt(?DateTime $notifyInactivityAt): self
    {
        $this->notifyInactivityAt = $notifyInactivityAt;
        return $this;
    }

}
