<?php

namespace eXpansion\Framework\Core\Services\Application;

use Symfony\Component\Console\Output\OutputInterface;

interface RunInterface
{
    public function init(OutputInterface $output);

    public function run();

    public function stopApplication();
}