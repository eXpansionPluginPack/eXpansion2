<?php


namespace eXpansion\Framework\GameManiaplanet\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use eXpansion\Framework\Core\Model\CompatibilityCheckDataProviderInterface;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;
use Maniaplanet\DedicatedServer\Xmlrpc\FaultException;
use Psr\Log\LoggerInterface;


/**
 * Class ScriptApiVersionSetterDataProvider
 *
 * @package eXpansion\Framework\GameManiaplanet\DataProviders;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class ScriptApiVersionSetterDataProvider extends AbstractDataProvider implements CompatibilityCheckDataProviderInterface
{
    /** @var Factory */
    protected $factory;

    /** @var LoggerInterface */
    protected $logger;

    /** @var string */
    protected $apiVersion;

    /**
     * ScriptApiVersionSetterDataProvider constructor.
     *
     * @param Factory $factory
     * @param LoggerInterface $logger
     * @param string $apiVersion
     */
    public function __construct(Factory $factory, LoggerInterface $logger, string $apiVersion = '2.4.0')
    {
        $this->factory = $factory;
        $this->logger = $logger;
        $this->apiVersion = $apiVersion;
    }


    /**
     * @inheritdoc
     */
    public function isCompatible(Map $map): bool
    {
        try {
            $this->factory->getConnection()->triggerModeScriptEvent("XmlRpc.SetApiVersion", [$this->apiVersion]);
            return true;
        } catch (FaultException $e) {
            $this->logger->warning(
                "Can't set script api version. This might be normal if you are using a custom script.",
                ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]
            );
        }

        return true;
    }
}