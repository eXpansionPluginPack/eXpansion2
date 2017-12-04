<?php


namespace eXpansion\Bundle\VoteManager\DependencyInjection\Compiler;

use eXpansion\Bundle\VoteManager\Services\VoteService;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


/**
 * Class VoteFactoriesPass
 *
 * @package eXpansion\Bundle\VoteManager\DependencyInjection\Compiler;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class VoteFactoriesPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(VoteService::class)) {
            return;
        }
        $definition = $container->getDefinition(VoteService::class);

        $voteFactorieReferences = [];
        $voteFactories = $container->findTaggedServiceIds('expansion.vote_manager.vote');
        foreach ($voteFactories as $voteFactoryId => $data) {
            $voteFactorieReferences[] = new Reference($voteFactoryId);
        }

        $definition->replaceArgument('$voteFactories', $voteFactorieReferences);
    }
}