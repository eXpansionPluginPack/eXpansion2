<?php


namespace eXpansion\Framework\Core\DependencyInjection\Compiler;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use oliverde8\AsynchronousJobs\Job\Curl;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;


/**
 * Class GameTitleFetchPass
 *
 * @package eXpansion\Framework\Core\DependencyInjection\Compiler;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class GameTitleFetchPass implements CompilerPassInterface
{
    const MAPPINGS_FILE = "title_game_mappings.json";

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $fileSytem = new Filesystem(new Local(realpath($container->getParameter('kernel.root_dir') . "/../var")));
        $fileSytem->createDir('expansion');
        $fileSytem = new Filesystem(new Local(realpath($container->getParameter('kernel.root_dir') . "/../var/expansion")));

        $this->fetchDataFromRemote($fileSytem, $container);

        if ($fileSytem->has(self::MAPPINGS_FILE)) {
            $data = json_decode($fileSytem->read(self::MAPPINGS_FILE), true);
            if ($data) {
                $values = [];
                foreach ($data as $titleId => $titleInformation) {
                    $values[$titleId] = $titleInformation['game'];
                }

                $container->setParameter("expansion.storage.gamedata.titles", $values);
            }
        }
    }

    /**
     * Fetch title information from eXp api.
     *
     * @param Filesystem       $fileSytem
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    protected function fetchDataFromRemote(Filesystem $fileSytem, ContainerBuilder $container)
    {
        $curl = new Curl();
        $curl->setUrl("https://mp-expansion.com/api/maniaplanet/games");
        $curl->run();
        if ($curl->getCurlInfo()['http_code'] == 200) {
            $fileSytem->put(self::MAPPINGS_FILE, $curl->getResponse());
        } else {
            echo "Can't fetch title mappings from expansion website!\n";
        }
    }
}