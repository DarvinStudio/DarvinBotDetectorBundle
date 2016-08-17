<?php

namespace Darvin\BotDetectorBundle\DependencyInjection;

use Darvin\BotDetectorBundle\Entity\DetectedBot;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DarvinBotDetectorExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
//        $configuration = new Configuration();
//        $config = $this->processConfiguration($configuration, $configs);
//
//        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
//
//        foreach ([
//        ] as $resource) {
//            $loader->load($resource.'.yml');
//        }
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['DarvinAdminBundle'])) {
            $container->prependExtensionConfig('darvin_admin', [
                'sections' => [
                    [
                        'alias'  => 'bot',
                        'entity' => DetectedBot::DETECTED_BOT_CLASS,
                        'config' => '@DarvinBotDetectorBundle/Resources/config/admin/detected_bot.yml',
                    ],
                ],
            ]);
        }
    }
}
