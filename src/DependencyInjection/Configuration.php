<?php

namespace WebEtDesign\RgpdBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('wd_rgpd');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('export')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('zip_private_path')->defaultValue('var/export_data')->end()
                    ->end()
                ->end()
            ->end()
        ;

        $rootNode
            ->children()
                ->arrayNode('old_password_reminder')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('password_validity_duration_before_notify')
                            ->defaultValue('12 month')
                        ->end()
                        ->scalarNode('duration_between_notify')->defaultValue('6 month')->end()
                        ->scalarNode('reset_password_route')->defaultValue('sonata_user_admin_resetting_request')->end()
                    ->end()
                ->end()
            ->end()
        ;

        $rootNode
            ->children()
                ->arrayNode('inactivity')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('userClass')->defaultValue('App\Entity\User')->end()
                        ->scalarNode('duration')->defaultValue('12 month')->end()
                        ->scalarNode('duration_before_anonymization')->defaultValue('1 month')->end()
                        ->scalarNode('email_cto_route')->defaultNull()->end()
                        ->scalarNode('callback')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;

        $rootNode
            ->children()
                ->arrayNode('security')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('admin_delay')->defaultValue(15)->end()
                        ->scalarNode('admin_max_attempts')->defaultValue(5)->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
