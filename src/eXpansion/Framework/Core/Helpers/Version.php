<?php

namespace eXpansion\Framework\Core\Helpers;

use PackageVersions\Versions;
use Symfony\Component\Process\Process;

/**
 * Class Version
 *
 * @author    de Cramer Oliver<oiverde8@gmail.com>
 * @copyright 2018 Oliverde8
 * @package eXpansion\Framework\Core\Helpers
 */
class Version
{
    protected $expansionVersion = null;

    protected $expansionBranch = null;

    protected $expansionSha = null;

    /**
     * Get current expansion version.
     *
     * @return null|string
     */
    public function getExpansionVersion()
    {
        if (!is_null($this->expansionVersion)) {
            return $this->expansionVersion;
        }

        // First check if we are in a git directory. This would be the case if we are in a
        // full manual install(not recommended) or on a dev machine.
        $process = new Process('git --version');
        $process->run();
        if ($process->isSuccessful()) {
            $branch = $this->getBranch();
            $sha = $this->getSha();
            $this->expansionVersion = "2.0.0.x-$branch@$sha";
        } else {
            $this->expansionVersion = Versions::getVersion('expansion-mp/expansion');
        }

        return $this->expansionVersion;
    }

    /**
     * Get a shorten version of the expansion version.
     *
     * @return mixed
     */
    public function getShortExpansionVersion()
    {
        return explode('@', $this->getExpansionVersion())[0];
    }

    /**
     * Get current branch.
     *
     * @return null|string
     */
    protected function getBranch()
    {
        if (is_null($this->expansionBranch)) {
            $process = new Process('git rev-parse --abbrev-ref HEAD');
            $process->run();

            $this->expansionBranch = trim($process->getOutput());
        }

        return $this->expansionBranch;
    }

    /**
     * Get current commit sha.
     *
     * @return null|string
     */
    protected function getSha()
    {
        if (is_null($this->expansionSha)) {
            $process = new Process('git rev-parse HEAD');
            $process->run();

            $this->expansionSha = trim($process->getOutput());
        }

        return $this->expansionSha;
    }
}