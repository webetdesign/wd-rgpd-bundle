<?php

namespace WebEtDesign\RgpdBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Twig\Environment;
use WebEtDesign\MailerBundle\Entity\Mail;
use Doctrine\Persistence\ObjectManager;

class MailFixtures extends Fixture
{

    private Environment $environment;

    /**
     * MailFixtures constructor.
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    public function load(ObjectManager $manager)
    {
        $m1 = new Mail();
        $m1
            ->setName('Routine rappel mise à jour mot de passe obsolète')
            ->setEvent('ROUTINE_CHANGE_OLD_PASSWORD_REMINDER')
            ->setTo('__user.email__')
            ->setFrom('noreply@webetdesign.com')
            ->setTitle('Mise à jour mot de passe obsolète')
            ->setOnline(false)
            ->setContentHtml(file_get_contents(realpath(__DIR__ . '/../Resources/views/ROUTINE_CHANGE_OLD_PASSWORD_REMINDER.html.twig')))
            ->setContentTxt(file_get_contents(realpath(__DIR__ . '/../Resources/views/ROUTINE_CHANGE_OLD_PASSWORD_REMINDER.txt.twig')))
        ;
        $manager->persist($m1);

        $m2 = new Mail();
        $m2
            ->setName("Routine notification d'inactivité")
            ->setEvent('ROUTINE_INACTIVITY_NOTIFICATION')
            ->setTo('__user.email__')
            ->setFrom('noreply@webetdesign.com')
            ->setTitle("Notification d'inactivité")
            ->setOnline(false)
            ->setContentHtml(file_get_contents(realpath(__DIR__ . '/../Resources/views/ROUTINE_INACTIVITY_NOTIFICATION.html.twig')))
            ->setContentTxt(file_get_contents(realpath(__DIR__ . '/../Resources/views/ROUTINE_INACTIVITY_NOTIFICATION.txt.twig')))
        ;

        $manager->persist($m2);
        $manager->flush();

    }
}
