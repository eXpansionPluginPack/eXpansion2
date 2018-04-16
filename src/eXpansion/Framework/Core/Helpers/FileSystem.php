<?php

namespace eXpansion\Framework\Core\Helpers;

use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use League\Flysystem\Adapter\Local;
use League\Flysystem\FilesystemInterface;
use Maniaplanet\DedicatedServer\Connection;

/**
 * Class FileSystem
 *
 * @package eXpansion\Framework\Core\Helpers;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class FileSystem
{
    const CONNECTION_TYPE_LOCAL = 'local';
    const CONNECTION_TYPE_REMOTE = 'remote';

    /** @var Factory */
    protected $factory;

    /** @var string */
    protected $connectionType;

    /** @var array */
    protected $adapterParams;

    /** @var FilesystemInterface */
    protected $localAdapter;

    /** @var FilesystemInterface  */
    protected $remoteAdapter;

    /**
     * FileSystem constructor.
     *
     * @param Factory          $factory
     * @param string              $connectionType
     * @param FilesystemInterface $remoteAdapter
     */
    public function __construct(Factory $factory, string $connectionType, FilesystemInterface $remoteAdapter)
    {
        $this->factory = $factory;
        $this->connectionType = $connectionType;
        $this->remoteAdapter = $remoteAdapter;
    }

    /**
     * Get Filesystem adapter for the user data directory of the dedicated server.
     *
     * @return FilesystemInterface
     */
    public function getUserData() : FilesystemInterface
    {
        if ($this->connectionType == self::CONNECTION_TYPE_LOCAL) {
            return $this->getLocalAdapter();
        } else {
            return $this->remoteAdapter;
        }
    }

    /**
     * Get local adapter if dedicated is installed on same host.
     *
     * @return FilesystemInterface
     */
    protected function getLocalAdapter() : FilesystemInterface
    {
        if (is_null($this->localAdapter)) {
            $dir = $this->factory->getConnection()->getMapsDirectory();
            $this->localAdapter = new \League\Flysystem\Filesystem(new Local($dir.'/../'));
        }

        return $this->localAdapter;
    }
}
