<?php

namespace eXpansion\Framework\MlComposeBundle\Command;

use eXpansion\Framework\Core\Plugins\Gui\WindowHelpFactory;
use Oliverde8\PageCompose\Service\BlockDefinitions;
use Oliverde8\PageCompose\Service\UiComponents;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TestUiCommand
 *
 * @author    de Cramer Oliver<oiverde8@gmail.com>
 * @copyright 2018 Oliverde8
 * @package eXpansion\Framework\MlComposeBundle\Command
 */
class TestUiCommand extends Command
{
    /** @var BlockDefinitions */
    protected $blockDefinitions;

    /** @var WindowHelpFactory */
    protected $helpFactory;

    /** @var UiComponents */
    protected $uiComponents;

    /**
     * TestUiCommand constructor.
     *
     * @param BlockDefinitions $blockDefinitions
     * @param UiComponents $uiComponents
     */
    public function __construct(BlockDefinitions $blockDefinitions, UiComponents $uiComponents, WindowHelpFactory $helpFactory)
    {
        parent::__construct();

        $this->blockDefinitions = $blockDefinitions;
        $this->uiComponents = $uiComponents;
        $this->helpFactory = $helpFactory;
    }


    protected function configure()
    {
        $this->setName('eXpansion:debug:ml-compose')
            ->setDescription("Debug expansion ui builder.");

        $this->addArgument('block', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->blockDefinitions->getPageBlocks('base.window', []) as $block) {
            echo $this->uiComponents->display($block, $this->helpFactory, $this);
        }
    }
}