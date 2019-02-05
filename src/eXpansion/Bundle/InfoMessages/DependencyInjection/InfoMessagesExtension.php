<?php

namespace eXpansion\Bundle\InfoMessages\DependencyInjection;

use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

class InfoMessagesExtension extends Extension
{
    const ABSTRACT_SERVICE_DEFINITION_ID = 'eXpansion.info_messages.config.messages.abstract';

    const CONFIG_PATH_PREFIX = 'eXpansion/Messages/InfoMessages/';

    /**
     * Loads a specific configuration.
     *
     * @param array $configs An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('configs.yml');
        $loader->load('plugins.yml');

        $locales = $container->getParameter('expansion.core.supported_locales');
        $defaultMessages = $container->getParameter('eXpansion_info_messages_default');

        foreach ($locales as $locale) {
            $id = "eXpansion.info_messages.config.messages.$locale";

            $service = $container->setDefinition($id, new ChildDefinition(self::ABSTRACT_SERVICE_DEFINITION_ID))
                ->replaceArgument('$path', self::CONFIG_PATH_PREFIX . "$locale")
                ->replaceArgument('$name', "expansion_info_messages.config.messages.$locale.name")
                ->addTag("expansion.config");

            if (isset($defaultMessages[$locale])) {
                $service->replaceArgument('$defaultValue', $defaultMessages[$locale]);
            }
        }
    }
}
