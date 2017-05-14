<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 14/05/2017
 * Time: 12:04
 */

namespace eXpansion\Framework\Core\Model\Gui;

use FML\Types\Renderable;

/**
 * Class ManiaScript
 *
 * @TODO add other helper methods to clean escape script sutff.
 *
 * @package eXpansion\Framework\Core\Model\Gui;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class ManiaScript implements Renderable
{
    /** @var string  */
    protected $filePath;

    /** @var string  */
    protected $id;

    /** @var array */
    public $params;

    /**
     * ManiaScript constructor.
     *
     * @param string $filePath
     */
    public function __construct($filePath, $params)
    {
        $this->filePath = $filePath;
        $this->id = spl_object_hash($this);
        $this->params = $params;
    }

    /**
     * Get unique variable name.
     *
     * @param string $name
     *
     * @return string
     */
    public function getVarN($name)
    {
        return $this->id . "_$name";
    }

    /**
     * Generate script content
     *
     * @return string
     */
    public function __toString()
    {
        ob_start();

        echo "\n";
        echo "/**************************************************\n";
        echo " *     eXpansion : 2.*.*\n";
        echo "**************************************************/\n";
        echo "\n";

        include $this->filePath;

        $script = ob_get_contents();
        ob_end_clean();

        return $script;
    }

    /**
     * Render the XML element
     *
     * @param \DOMDocument $domDocument DOMDocument for which the XML element should be rendered
     *
     * @return \DOMElement
     */
    public function render(\DOMDocument $domDocument)
    {
        $scriptXml  = $domDocument->createElement("script");
        $scriptText = $this->__toString();

        $scriptComment = $domDocument->createComment($scriptText);
        $scriptXml->appendChild($scriptComment);

        return $scriptXml;
    }
}