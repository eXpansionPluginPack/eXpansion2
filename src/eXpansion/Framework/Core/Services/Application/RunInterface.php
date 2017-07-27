<?php

namespace eXpansion\Framework\Core\Services\Application;

use Symfony\Component\Console\Output\OutputInterface;

interface RunInterface
{
    /**
     * @param OutputInterface $output
     * @return mixed
     */
    public function init(OutputInterface $output);

    /**
     * @return mixed
     */
    public function run();

    /**
     * @return mixed
     */
    public function stopApplication();
}
