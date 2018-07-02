<?php

namespace eXpansion\Framework\MlComposeBundle\DependencyInjection;

use Oliverde8\PageCompose\Service\BlockDefinitions;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;


/**
 * Class eXpansionMlComposeExtension
 *
 * @author    de Cramer Oliver<oiverde8@gmail.com>
 * @copyright 2018 Oliverde8
 * @package eXpansion\Framework\MlComposeBundle\DependencyInjection
 */
class eXpansionMlComposeExtension extends Extension
{
    /**
     * @inheritdoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('components.yml');
        $loader->load('helpers.yml');

        $bundles = $container->getParameter('kernel.bundles');
        $finder = new Finder();

        $pageLayout = [];

        foreach ($bundles as $bundle) {
            $reflection = new \ReflectionClass($bundle);
            $dir = dirname($reflection->getFilename()) . '/Resources/config/ml-compose/';

            if (is_dir($dir)) {
                $files = $finder->files()
                    ->name("*.xml")
                    ->in($dir);

                foreach ($files as $file) {
                    $pageLayout += $this->parseFile($file, $container);
                }
            }
        }

        $container->getDefinition(BlockDefinitions::class)->setArgument('$buildFactories', $pageLayout);
    }

    protected function parseFile($filePath, ContainerBuilder $container)
    {
        $xsdPath = realpath(__DIR__ . "/../Resources/config/page-compose.xsd");
        $dom = new \DOMDocument();
        $dom->loadXML(str_replace("exp:ml_compose_bundle:page-compose.xsd", $xsdPath, file_get_contents($filePath)));

        if (!$dom->schemaValidate($xsdPath)) {
            var_dump($errors = libxml_get_errors());
            echo "File contains errors";
            die(2);
            // TODO do this better.
        }

        $xml = simplexml_load_file($filePath);
        return $this->processBlock($xml, $container);
    }

    protected function processBlock(\SimpleXMLElement $element, ContainerBuilder $container, $parent = null) {
        $blockDefinition = [];

        if ($parent) {
            $blockDefinition['parent'] = $parent;
        }

        if (isset($element->attributes()['component'])) {
            $blockDefinition['component'] = (string) $element->attributes()['component'];
        } else {
            $blockDefinition['extends'] = (string) $element->attributes()['extends'];
        }

        if (isset($element->attributes()['alias'])) {
            $blockDefinition['alias'] = (string) $element->attributes()['alias'];
        }

        foreach ($element->argument as $argument) {
            $name = (string) $argument->attributes()['name'];

            switch ($this->getXsiType($argument)) {
                case "expr":
                    $blockDefinition['config']['expr'][$name] = (string) $argument;
                    break;

                case "action":
                    $blockDefinition['config']['action'][$name]['method'] = (string)$argument->method[0];
                    if (isset($argument->service[0])) {
                        // Use symfony to actually get a reference.
                        $blockDefinition['config']['action'][$name]['service'] = new Reference((string)$argument->service[0]);
                    }
                    break;

                case "args":
                    $blockDefinition['config']['args'][$name] = [];
                    foreach ($argument->arg as $arg) {
                        $blockDefinition['config']['args'][$name][] = (string) $arg;
                    }
                    break;

                default:
                    $blockDefinition['config']['def'][$name] = (string) $argument;
            }
        }

        $blockDefinitions = [
            (string) $element->attributes()['id'] => $blockDefinition,
        ];

        foreach ($element->block as $block) {
            $blockDefinitions += $this->processBlock($block, $container, (string) $element->attributes()['id']);
        }

        return $blockDefinitions;
    }

    protected function getXsiType(\SimpleXMLElement $element)
    {
        $namespaces = $element->getNamespaces();

        if (isset($namespaces['xsi']) && isset($element->attributes($namespaces['xsi'])['type'])) {
            return (string) $element->attributes($element->getNamespaces()['xsi'])['type'];
        }

        return null;
    }

}