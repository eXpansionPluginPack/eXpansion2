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

        $locales = $container->getParameter('expansion.core.supported_locales');

        foreach ($locales as $locale) {
            $id = "eXpansion.info_messages.config.messages.$locale";

            $container->setDefinition($id, new ChildDefinition(self::ABSTRACT_SERVICE_DEFINITION_ID))
                ->replaceArgument('$path', "eXpansion/Messages/InfoMessages/$locale")
                ->replaceArgument('$name', "expansion_info_messages.config.messages.$locale.name")
                ->addTag("expansion.config");
        }
    }
}
