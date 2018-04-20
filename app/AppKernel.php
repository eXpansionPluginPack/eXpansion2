<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    /**
     * @inheritdoc
     */
    public function __construct(string $environment, bool $debug)
    {
        parent::__construct($environment, $debug);

        // Force enabling GC.
        gc_enable();
        if (!gc_enabled()) {
            die('Garbage collector couldn\'t be enabled');
        }
    }

    public function registerBundles()
    {
        /* Register symfony bundles & eXpansion core bundles */
        $bundles = $this->registerCoreBundles();

        /* Register eXpansion Base Bundles */
        $bundles[] = new \eXpansion\Bundle\CustomChat\CustomChatBundle();
        $bundles[] = new \eXpansion\Bundle\CustomUi\CustomUiBundle();
        $bundles[] = new \eXpansion\Bundle\AdminChat\AdminChatBundle();

        /* Register eXpansion Plugins */
        $bundles[] = new \eXpansion\Bundle\LocalRecords\LocalRecordsBundle();
        $bundles[] = new \eXpansion\Bundle\Maps\MapsBundle();
        $bundles[] = new \eXpansion\Bundle\Players\PlayersBundle();
        $bundles[] = new \eXpansion\Bundle\JoinLeaveMessages\JoinLeaveMessagesBundle();
        $bundles[] = new \eXpansion\Bundle\Emotes\EmotesBundle();
        $bundles[] = new \eXpansion\Bundle\Menu\MenuBundle();
        $bundles[] = new \eXpansion\Bundle\Admin\AdminBundle();
        $bundles[] = new \eXpansion\Bundle\LocalMapRatings\LocalMapRatingsBundle();

        $bundles[] = new \eXpansion\Bundle\WidgetCurrentMap\WidgetCurrentMapBundle();
        $bundles[] = new \eXpansion\Bundle\WidgetBestCheckpoints\WidgetBestCheckpointsBundle();


        $bundles[] = new \eXpansion\Bundle\VoteManager\VoteManagerBundle();
        $bundles[] = new \eXpansion\Bundle\MxKarma\MxKarmaBundle();

//        $bundles[] = new \eXpansion\Bundle\ImmersiveWindows\ImmersiveWindowsBundle();

        /* Register experimental bundles */
        $bundles[] = new \eXpansionExperimantal\Bundle\Dedimania\DedimaniaBundle();
        $bundles[] = new \eXpansionExperimantal\Bundle\WidgetBestRecords\WidgetBestRecordsBundle();

        /* Register test bundles. */
        $bundles[] = new \eXpansion\Bundle\Acme\AcmeBundle();


        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new \eXpansion\Bundle\DeveloperTools\DeveloperToolsBundle();
        }

        return $bundles;
    }

    protected function registerCoreBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Propel\Bundle\PropelBundle\PropelBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            // Add SF Community Bundles
            new \Oneup\FlysystemBundle\OneupFlysystemBundle(),

            // And add eXpansion core.
            new \eXpansion\Framework\Core\eXpansionCore(),
            new \eXpansion\Framework\GameManiaplanet\eXpansionGameManiaplanetBundle(),
            new \eXpansion\Framework\GameTrackmania\eXpansionGameTrackmaniaBundle(),
            new \eXpansion\Framework\GameShootmania\eXpansionGameShootmaniaBundle(),
            new \eXpansion\Framework\GameCurrencyBundle\eXpansionGameCurrencyBundle(),
            new \eXpansion\Framework\AdminGroups\eXpansionAdminGroupsBundle(),
            new \eXpansion\Framework\Gui\eXpansionGuiBundle(),
            new \eXpansion\Framework\PlayersBundle\eXpansionFrameworkPlayersBundle(),
            new \eXpansion\Framework\Config\eXpansionConfig(),
            new \eXpansion\Framework\Notifications\eXpansionNotificationsBundle(),
            new \eXpansion\Framework\MlComposeBundle\eXpansionMlComposeBundle(),
        ];

        // Also add debug help bundles.
        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
