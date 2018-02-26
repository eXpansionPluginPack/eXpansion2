<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 25.2.2018
 * Time: 19.09
 */

namespace eXpansion\Framework\GameCurrencyBundle\Structures;


use eXpansion\Framework\GameCurrencyBundle\Model\Gamecurrency;

class CurrencyEntry
{

    /** @var Gamecurrency */
    protected $bill;
    protected $successCallback;
    protected $failureCallback;

    /**
     * @return Gamecurrency
     */
    public function getBill()
    {
        return $this->bill;
    }

    /**
     * @param mixed $bill
     */
    public function setBill(Gamecurrency $bill)
    {
        $this->bill = $bill;
    }

    /**
     * @return mixed
     */
    public function getSuccessCallback()
    {
        return $this->successCallback;
    }

    /**
     * @param mixed $successCallback
     */
    public function setSuccessCallback($successCallback)
    {
        $this->successCallback = $successCallback;
    }

    /**
     * @return mixed
     */
    public function getFailureCallback()
    {
        return $this->failureCallback;
    }

    /**
     * @param mixed $failureCallback
     */
    public function setFailureCallback($failureCallback)
    {
        $this->failureCallback = $failureCallback;
    }

}