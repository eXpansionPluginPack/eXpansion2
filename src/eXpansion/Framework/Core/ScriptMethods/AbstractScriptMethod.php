<?php

namespace eXpansion\Framework\Core\ScriptMethods;

use eXpansion\Framework\Core\DataProviders\MethodScriptDataProviderInterface;

/**
 * Class AbstractScriptMethod
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\Core\ScriptMethods
 */
class AbstractScriptMethod
{
    /** @var array | null */
    protected $currentData = null;

    /** @var bool  */
    protected $callMade = false;

    /** @var MethodScriptDataProviderInterface */
    protected $dataProvider;

    /** @var callback[] */
    protected $toDispatch;

    /**
     * Get TM.Scores or SM.Scores.
     *
     * @param callback $function
     *
     * @return void
     */
    public function get($function)
    {
        $this->toDispatch[] = $function;

        if (is_null($this->currentData) && !$this->callMade) {
            $this->callMade = true;
            $this->dataProvider->request();
            return;
        }

        $this->dispatchData();
    }


    /**
     * Set current stores.
     *
     * @param array $scores
     *
     * @return mixed
     */
    public function set($scores)
    {
        $this->currentData = $scores;
        $this->dispatchData();
    }

    /**
     * Set current data provider.
     *
     * @param MethodScriptDataProviderInterface $dataProvider
     *
     * @return mixed
     */
    public function setCurrentDataProvider(MethodScriptDataProviderInterface $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    /**
     * Dispatch data.
     */
    protected function dispatchData()
    {
        foreach ($this->toDispatch as $callback) {
            $callback($this->currentData);
        }

        $this->toDispatch = [];
        $this->callMade = false;
    }
}
