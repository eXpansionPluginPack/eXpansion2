<?php

namespace eXpansion\Framework\MlComposeBundle\Command;

use eXpansion\Framework\Core\Model\Gui\FmlManialinkFactoryContext;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\Gui\MlBuilderFactory;
use eXpansion\Framework\Core\Plugins\Gui\WindowHelpFactory;
use eXpansion\Framework\Core\Plugins\GuiHandler;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
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

    /** @var FmlManialinkFactoryContext */
    protected $fmlMlFactoryContext;

    /** @var UiComponents */
    protected $uiComponents;

    /** @var GuiHandler */
    protected $guiHandler;

    protected $dispatcher;

    /**
     * TestUiCommand constructor.
     *
     * @param BlockDefinitions $blockDefinitions
     * @param UiComponents $uiComponents
     */
    public function __construct(
        BlockDefinitions $blockDefinitions,
        UiComponents $uiComponents,
        FmlManialinkFactoryContext $fmlMlFactoryContext,
        GuiHandler $guiHandler,
        Dispatcher $dispatcher
    ) {
        parent::__construct();

        $this->blockDefinitions = $blockDefinitions;
        $this->uiComponents = $uiComponents;
        $this->fmlMlFactoryContext = $fmlMlFactoryContext;
        $this->guiHandler = $guiHandler;
        $this->dispatcher = $dispatcher;
    }


    protected function configure()
    {
        $this->setName('eXpansion:debug:ml-compose')
            ->setDescription("Debug expansion ui builder.");

        $this->addArgument('block', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $factory = new MlBuilderFactory(
            "Test",
            $input->getArgument('block'),
            100, // TODO take these from input.
            100,
            null,
            null,
            $this->fmlMlFactoryContext,
            $this->blockDefinitions,
            $this->uiComponents
        );
        $group = new Group("test", $this->dispatcher);

        $factory->create($group);

        echo $this->guiHandler->getManialink($group, $factory)->getXml();
        echo "\n";
    }
}