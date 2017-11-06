<?php

namespace eXpansion\Framework\Core\DataProviders;

/**
 * Interface MethodScriptDataProviderInterface
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\Core\DataProviders
 */
interface MethodScriptDataProviderInterface
{
    /**
     * Request call to fetch something..
     *
     * @return null
     */
    public function request();
}
