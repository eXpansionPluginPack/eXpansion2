<?php

namespace eXpansion\Framework\Core\Exceptions;

use Throwable;

/**
 * Class PlayerException, is a special type of extension that needs to be used only to display an error message to a
 * player. These exceptions should not be logged but always catched.
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Framework\Core\Exceptions
 */
class PlayerException extends ApplicationException
{
    /** @var string|null */
    protected $translatableMessage = null;

    /**
     * PlayerException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param null $translatableMessage
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null, $translatableMessage = null)
    {
        parent::__construct($message, $code, $previous);
        $this->translatableMessage = $translatableMessage;
    }

    /**
     * Get error message to translate and send to users.
     *
     * @return string
     */
    public function getTranslatableMessage()
    {
        return is_null($this->translatableMessage) ? $this->message : $this->translatableMessage;
    }
}