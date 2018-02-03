<?php

namespace eXpansion\Framework\Core\Services\Application;

use Symfony\Component\Console\Output\OutputInterface;

interface RunInterface
{
    /**
     * @param OutputInterface $output
     *
     * @return mixed
     */
    public function init(OutputInterface $output);

    /**
     * Run expansion
     *
     * @return void
     */
    public function run();

    /**
     * Stop application.
     *
     * @return void
     */
    public function stopApplication();
}
