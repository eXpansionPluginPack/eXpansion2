<?php

namespace eXpansion\Framework\Core\Model;

/**
 * interface DestroyableObject
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Framework\Core\Model
 */
interface DestroyableObject
{
    /**
     * Prepare object so that it's destroyed easier.
     *
     * Generally GC will do this automatically, we are trying to help it so that it happens faster.
     *
     * @return mixed
     */
    public function destroy();
}
