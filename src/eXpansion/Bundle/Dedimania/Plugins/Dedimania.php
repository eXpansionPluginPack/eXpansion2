<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 27.2.2018
 * Time: 10.50
 */

namespace eXpansion\Bundle\Dedimania\Plugins;


use eXpansion\Bundle\Dedimania\Services\DedimaniaService;
use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;

class Dedimania implements StatusAwarePluginInterface
{
    /**
     * @var DedimaniaService
     */
    private $dedimaniaService;
    /**
     * @var ConfigInterface
     */
    private $enabled;
    /**
     * @var ConfigInterface
     */
    private $apikey;
    /**
     * @var ConfigInterface
     */
    private $serverLogin;

    /**
     * Dedimania constructor.
     * @param ConfigInterface  $enabled
     * @param ConfigInterface  $apikey
     * @param ConfigInterface  $serverLogin
     * @param DedimaniaService $dedimaniaService
     */
    public function __construct(
        ConfigInterface $enabled,
        ConfigInterface $apikey,
        ConfigInterface $serverLogin,
        DedimaniaService $dedimaniaService
    ) {
        $this->dedimaniaService = $dedimaniaService;
        $this->enabled = $enabled;
        $this->apikey = $apikey;
        $this->serverLogin = $serverLogin;
    }

    /**
     * Set the status of the plugin
     *
     * @param boolean $status
     *
     * @return void
     * @throws \Exception
     */
    public function setStatus($status)
    {
        if ($status && $this->enabled->get()) {
            try {
                $this->dedimaniaService->openSession($this->serverLogin->get(), $this->apikey->get());
            } catch (\Exception $e) {

            }
        }
    }
}