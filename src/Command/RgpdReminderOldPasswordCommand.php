<?php

namespace WebEtDesign\RgpdBundle\Command;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use WebEtDesign\RgpdBundle\Event\RoutineChangeOldPasswordReminder;

class RgpdReminderOldPasswordCommand extends Command
{
    protected static $defaultName        = 'rgpd:reminder-old-password';
    protected static $defaultDescription = 'Notifies the user if their password has not been updated for some time.';
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    private                        $config;
    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;
    /**
     * @var RouterInterface
     */
    private RouterInterface $router;

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription);
    }

    /**
     * @inheritDoc
     */
    public function __construct(
        string $name = null,
        EntityManagerInterface $em,
        EventDispatcherInterface $eventDispatcher,
        RouterInterface $router,
        $config
    ) {
        parent::__construct($name);
        $this->em              = $em;
        $this->config          = $config;
        $this->eventDispatcher = $eventDispatcher;
        $this->router          = $router;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io   = new SymfonyStyle($input, $output);

        $now          = new DateTime('now');
        $validityDate = new DateTime('now -' . $this->config['password_validity_duration_before_notify']);
        $notifyDate   = new DateTime('now -' . $this->config['duration_between_notify']);

        $qb = $this->em->createQueryBuilder();
        $qb->select('u')
            ->from(User::class, 'u')
            ->andWhere('u.enabled = true')
            ->andWhere('u.lastUpdatePassword < :validityDate')
            ->andWhere($qb->expr()->orX(
                $qb->expr()->lt('u.notifyUpdatePasswordAt', ':notifyDate'),
                $qb->expr()->isNull('u.notifyUpdatePasswordAt')
            ))
            ->setParameters(['validityDate' => $validityDate, 'notifyDate' => $notifyDate]);


        $users = $qb->getQuery()->getResult();

        /** @var User $user */
        foreach ($users as $user) {
            $diff = date_diff($now, $user->getLastUpdatePassword());

            $useTime =
                ($diff->y > 0 ? $diff->y . ' ' . ngettext('an', 'ans', $diff->y) . ' ' : '') .
                ($diff->m > 0 ? $diff->m . ' mois ' : '') . 'et ' .
                ($diff->d > 0 ? $diff->d . ' ' . ngettext('jour', 'jours', $diff->d) : '');

            $user->setNotifyUpdatePasswordAt($now);
            $this->em->persist($user);

            $event = new RoutineChangeOldPasswordReminder($user);
            $event->setUseTime($useTime);
            try {
                $reset_link = $this->router->generate(
                    $this->config['reset_password_route'], [], UrlGeneratorInterface::ABSOLUTE_URL
                );
                $event->setResetLink($reset_link);

            } catch (RouteNotFoundException $exception) {
            }

            $this->eventDispatcher->dispatch($event, RoutineChangeOldPasswordReminder::NAME);
        }

        $this->em->flush();

        $io->success(count($users) . ' ' . ngettext('user', 'users', count($users))
            . ' notified !');

        return 0;
    }
}
