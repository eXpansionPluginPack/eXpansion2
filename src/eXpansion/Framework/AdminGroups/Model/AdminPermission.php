<?php

namespace eXpansion\Framework\AdminGroups\Model;

/**
 * Class AdminPermission
 *
 * @author    de Cramer Oliver<oiverde8@gmail.com>
 * @copyright 2018 Oliverde8
 * @package eXpansion\Framework\AdminGroups\Model
 */
class AdminPermission
{
    /** @var string */
    protected $description;

    /**
     * AdminPermission constructor.
     *
     * @param string $description
     */
    public function __construct(string $description)
    {
        $this->description = $description;
    }


}