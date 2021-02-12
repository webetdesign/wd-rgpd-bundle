<?php


namespace WebEtDesign\RgpdBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use WebEtDesign\RgpdBundle\Repository\LoginAttemptRepository;

class CleanLoginAttempts extends Command
{
    protected static $defaultName = 'rgpd:clean-login-attempts';

    private LoginAttemptRepository $loginAttemptRepository;
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params, LoginAttemptRepository $loginAttemptRepository)
    {
        // best practices recommend to call the parent constructor first and
        // then set your own properties. That wouldn't work in this case
        // because configure() needs the properties set in this constructor
        $this->loginAttemptRepository = $loginAttemptRepository;
        $this->params = $params;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $delay = $this->params->get('security.admin_delay');

        $this->loginAttemptRepository->deleteOldLoginAttempts($delay);

        return 0;
    }
}
