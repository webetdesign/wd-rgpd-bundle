<?php

namespace WebEtDesign\RgpdBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use WebEtDesign\RgpdBundle\Annotations\Anonymizable;
use WebEtDesign\RgpdBundle\Annotations\Anonymizer;
use WebEtDesign\RgpdBundle\Annotations\Exportable;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class WDRgpdExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $processor     = new Processor();
        $config        = $processor->processConfiguration($configuration, $configs);

        $container->setParameter('wd_rgpd.export.zip_private_path',
            $config['export']['zip_private_path']);
        $container->setParameter('wd_rgpd.old_password_reminder', $config['old_password_reminder']);

        $container->setParameter(
            'wd_rgpd.security.admin_delay',
            $config['security']['admin_delay']
        );
        $container->setParameter(
            'wd_rgpd.security.admin_max_attempts',
            $config['security']['admin_max_attempts']
        );

        $container->setParameter(
            'wd_rgpd.inactivity.duration',
            $config['inactivity']['duration']
        );
        $container->setParameter(
            'wd_rgpd.inactivity.duration_before_anonymization',
            $config['inactivity']['duration_before_anonymization']
        );
        $container->setParameter(
            'wd_rgpd.inactivity.email_cto_route',
            $config['inactivity']['email_cto_route']
        );
        $container->setParameter(
            'wd_rgpd.inactivity.callback',
            $config['inactivity']['callback']
        );
        $container->setParameter(
            'wd_rgpd.inactivity.userClass',
            $config['inactivity']['userClass']
        );


        $loader = new Loader\YamlFileLoader($container,
            new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $bundles         = $container->getParameter('kernel.bundles');
        $exporterService = $container->getDefinition('WebEtDesign\RgpdBundle\Services\Exporter');
        $anonymizerService = $container->getDefinition('WebEtDesign\RgpdBundle\Services\Anonymizer');
        if (isset($bundles['SonataMediaBundle'])) {
            $loader->load('sonata_media_services.yaml');
            $exporterMediaService = $container->getDefinition('WebEtDesign\RgpdBundle\Exporter\ExporterSonataMedia');
            $exporterService->addMethodCall('addExporter',
                [$exporterMediaService, Exportable::TYPE_SONATA_MEDIA]);
        }

        if (isset($bundles['VichUploaderBundle'])) {
            $loader->load('vich_services.yaml');
            $exporterVichService = $container->getDefinition('WebEtDesign\RgpdBundle\Exporter\ExporterVich');
            $exporterService->addMethodCall('addExporter',
                [$exporterVichService, Exportable::TYPE_VICH_UPLOADER]);

            $anonymizerVichService = $container->getDefinition('WebEtDesign\RgpdBundle\Anonymizer\AnonymizerVich');
            $anonymizerService->addMethodCall('addAnonymizer', [$anonymizerVichService, Anonymizer::TYPE_VICH]);
        }
    }
}
