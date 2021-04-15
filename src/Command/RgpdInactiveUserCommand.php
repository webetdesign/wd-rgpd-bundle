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
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use WebEtDesign\RgpdBundle\Event\RoutineInactivityNotification;
use WebEtDesign\RgpdBundle\Services\AnonymizerInterface;

class RgpdInactiveUserCommand extends Command
{
    protected static $defaultName        = 'rgpd:inactive-user';
    protected static $defaultDescription = 'Notifies users if they have not logged in for a while. Anonymizes users who have not logged in after being notified.';
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;
    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;
    /**
     * @var RouterInterface
     */
    private RouterInterface $router;
    /**
     * @var AnonymizerInterface
     */
    private AnonymizerInterface $anonymizer;

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    public function __construct(
        EntityManagerInterface $em,
        ParameterBagInterface $parameterBag,
        EventDispatcherInterface $eventDispatcher,
        RouterInterface $router,
        AnonymizerInterface $anonymizer,
        string $name = null
    ) {
        parent::__construct($name);
        $this->em              = $em;
        $this->parameterBag    = $parameterBag;
        $this->eventDispatcher = $eventDispatcher;
        $this->router          = $router;
        $this->anonymizer      = $anonymizer;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $now               = new DateTime('now');
        $inactivityDate    = new DateTime('now -' . $this->parameterBag->get('wd_rgpd.inactivity.duration'));
        $anonymizationDate = new DateTime('now -' . $this->parameterBag->get('wd_rgpd.inactivity.duration_before_anonymization'));

        $userClass = $this->parameterBag->get('wd_rgpd.inactivity.userClass');

        $qb = $this->em->createQueryBuilder();
        $qb->select('u')
            ->from($userClass, 'u')
            ->andWhere('u.enabled = true')
            ->andWhere('u.lastLogin < :inactivityDate')
            ->andWhere($qb->expr()->isNull('u.notifyInactivityAt'))
            ->setParameters(['inactivityDate' => $inactivityDate]);

        $users  = $qb->getQuery()->getResult();
        $method = $this->parameterBag->get('wd_rgpd.inactivity.callback');
        $notified = 0;

        foreach ($users as $user) {
            if (($method && method_exists($user, $method) && $user->$method()) || (!$method) || !method_exists($user, $method)) {
                $notified ++;
                $user->setNotifyInactivityAt($now);
                $this->em->persist($user);

                $event = new RoutineInactivityNotification($user);
                try {
                    $ctoLink = $this->router->generate(
                        $this->parameterBag->get('wd_rgpd.inactivity.email_cto_route'), [],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    );
                    $event->setCtoLink($ctoLink);
                } catch (RouteNotFoundException $exception) {
                }

                $this->eventDispatcher->dispatch($event, RoutineInactivityNotification::NAME);
            }
        }

        $this->em->flush();

        $io->success($notified . ' ' . ngettext('user', 'users', $notified)
            . ' notified !');

        $qb = $this->em->createQueryBuilder();
        $qb->select('u')
            ->from($userClass, 'u')
            ->andWhere('u.enabled = true')
            ->andWhere('u.lastLogin < :inactivityDate')
            ->andWhere('u.notifyInactivityAt < :anonymizationDate')
            ->setParameters([
                'inactivityDate'    => $inactivityDate,
                'anonymizationDate' => $anonymizationDate
            ]);

        $users = $qb->getQuery()->getResult();

        foreach ($users as $user) {
            $this->anonymizer->anonimize($user);
            $this->em->persist($user);
        }

        $this->em->flush();

        $io->success(count($users) . ' ' . ngettext('user', 'users', count($users))
            . ' anonymize !');

        return 0;
    }
}
