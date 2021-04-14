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
        $mails = [
            0 => [
                'name' => "Routine rappel mise à jour mot de passe obsolète",
                'event' => 'ROUTINE_CHANGE_OLD_PASSWORD_REMINDER',
                'title' => "Mise à jour mot de passe obsolète"
            ],
            1 => [
                'name' => "Routine notification d'inactivité",
                'event' => 'ROUTINE_INACTIVITY_NOTIFICATION',
                'title' => "Notification d'inactivité"
            ],
            2 => [
                'name' => "Export données utilisateur",
                'event' => 'USER_EXPORT_DATA',
                'title' => "Export de vos données"
            ]
        ];

        foreach ($mails as $mail) {
            $m = new Mail();
            $m
                ->setName($mail['name'])
                ->setEvent($mail['event'])
                ->setTo('__user.email__')
                ->setFrom('noreply@webetdesign.com')
                ->setOnline(false)
                ->setTitle($mail['title'])
                ->setContentHtml(file_get_contents(realpath(__DIR__ . '/../Resources/views/' .$mail['event']. '.html.twig')))
                ->setContentTxt(file_get_contents(realpath(__DIR__ . '/../Resources/views/' . $mail['event'] . '.txt.twig')));
            ;

            $manager->persist($m);
            $manager->flush();
        }

    }
}
