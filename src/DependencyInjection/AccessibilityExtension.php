<?php

declare(strict_types=1);

namespace Pixel\AccessibilityBundle\DependencyInjection;

use Sulu\Bundle\PersistenceBundle\DependencyInjection\PersistenceExtensionTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class AccessibilityExtension extends Extension implements PrependExtensionInterface
{
    use PersistenceExtensionTrait;

    private const SULU_ADMIN_EXTENSION = 'sulu_admin';
    private const CONFIG_PATH = '/../Resources/config';
    private const FORMS_CONFIG_PATH = self::CONFIG_PATH . '/forms';

    public function prepend(ContainerBuilder $container): void
    {
        if (!$container->hasExtension(self::SULU_ADMIN_EXTENSION)) {
            return;
        }

        $container->prependExtensionConfig(
            self::SULU_ADMIN_EXTENSION,
            [
                'forms' => [
                    'directories' => [
                        __DIR__ . self::FORMS_CONFIG_PATH,
                    ],
                ],
                'resources' => [
                    'accessibility_settings' => [
                        'routes' => [
                            'detail' => 'accessibility.get_accessibility-settings',
                        ],
                    ],
                ],
            ]
        );
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $fileLocator = new FileLocator(__DIR__ . self::CONFIG_PATH);

        $xmlLoader = new XmlFileLoader($container, $fileLocator);
        $xmlLoader->load('services.xml');

        $yamlLoader = new YamlFileLoader($container, $fileLocator);
        $yamlLoader->load('services.yaml');
    }
}