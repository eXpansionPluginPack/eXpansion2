<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 14/05/2017
 * Time: 12:04
 */

namespace eXpansion\Framework\Core\Model\Gui;
use Symfony\Component\Config\FileLocator;


/**
 * Class ManiaScriptFactory
 **
 * @package eXpansion\Framework\Core\Model\Gui;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class ManiaScriptFactory
{
    protected $relativePath;

    protected $fileLocator;

    protected $className;

    /**
     * ManiaScriptFactory constructor.
     *
     * @param string $relativePath
     * @param FileLocator $fileLocator
     * @paramFileLocator $className
     */
    public function __construct($relativePath, FileLocator $fileLocator, $className)
    {
        $this->relativePath = $relativePath;
        $this->fileLocator = $fileLocator;
        $this->className = $className;
    }

    /**
     * Create an instance of script
     *
     * @param $params
     *
     * @return ManiaScript
     */
    public function createScript($params)
    {
        $className = $this->className;

        $filePath = $this->fileLocator->locate('@' . $this->relativePath);

        return new $className($filePath, $params);
    }
}