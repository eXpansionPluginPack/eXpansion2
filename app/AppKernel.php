<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
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
        $bundles[] = new \eXpansion\Bundle\JoinLeaveMessages\JoinLeaveMessagesBundle();
        $bundles[] = new \eXpansion\Bundle\Emotes\EmotesBundle();
        $bundles[] = new \eXpansion\Bundle\Menu\MenuBundle();
        $bundles[] = new \eXpansion\Bundle\Admin\AdminBundle();

//        $bundles[] = new \eXpansion\Bundle\MxKarma\MxKarmaBundle();


        /* Register test bundles. */
        $bundles[] = new \eXpansion\Bundle\Acme\AcmeBundle();

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
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            // And add eXpansion core.
            new \eXpansion\Framework\Core\eXpansionCore(),
            new \eXpansion\Framework\AdminGroups\eXpansionAdminGroupsBundle(),
            new \eXpansion\Framework\GameTrackmania\eXpansionGameTrackmaniaBundle(),
            new \eXpansion\Framework\GameManiaplanet\eXpansionGameManiaplanetBundle(),
            new \eXpansion\Framework\Gui\eXpansionGuiBundle(),
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
