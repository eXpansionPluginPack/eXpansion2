<?php

namespace eXpansion\Framework\Core\Services;

use eXpansion\Framework\Core\Helpers\ColorConversion;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Console to print in the console.
 *
 * @package eXpansion\Framework\Core\Services
 * @author Reaby
 */
class Console
{

    const black = "\e[0;30m";
    const b_black = "\e[30;1m";

    const red = "\e[0;31m";
    const b_red = "\e[1;31m";

    const green = "\e[0;32m";
    const b_green = "\e[1;32m";

    const yellow = "\e[0;33m";
    const b_yellow = "\e[1;33m";

    const blue = "\e[0;34m";
    const b_blue = "\e[1;34m";

    const magenta = "\e[0;35m";
    const b_magenta = "\e[1;35m";

    const cyan = "\e[0;36m";
    const b_cyan = "\e[1;36m";

    const white = "\e[0;37m";
    const b_white = "\e[1;37m";

    // define aliases for colors
    const error = "\e[37;1m\e[41m";
    const success = self::b_green;
    const normal = "\e[0m";
    const bold = self::b_white;


    /** @var OutputInterface */
    protected $consoleOutput;

    /** @var boolean Color console enabled */
    protected $colorEnabled;
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Console constructor.
     *
     * @param bool $colorEnabled
     */
    public function __construct($colorEnabled)
    {
        $this->colorEnabled = $colorEnabled;
    }


    /**
     * Initialize service with the console output.
     *
     * @param OutputInterface $consoleOutput
     */
    public function init(OutputInterface $consoleOutput, Dispatcher $dispatcher)
    {
        $this->consoleOutput = $consoleOutput;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @inheritdoc
     */
    public function writeln($string)
    {
        $this->write($string, true);
    }

    /**
     * @inheritdoc
     */
    public function write($string, $newline = false)
    {
        $this->dispatcher->dispatch("expansion.console.message", [$string]);

        if ($this->colorEnabled && $this->consoleOutput->isDecorated()) {

            $matches = array();
            preg_match_all("/\\$[A-Fa-f0-9]{3}/", $string, $matches);
            $split = preg_split("/\\$[A-Fa-f0-9]{3}/", $string);
            $out = "";

            foreach ($matches[0] as $i => $rgb) {
                $code = $this->doHslConvert(hexdec($rgb[1].$rgb[1]), hexdec($rgb[2].$rgb[2]), hexdec($rgb[3].$rgb[3]));
                $out .= $code.$this->stripStyles($split[$i + 1]);
                $end = $this->stripStyles($split[$i + 1]);
            }


            if (!empty($end)) {
                if ($end == $this->stripStyles(end($split))) {
                    $end = "";
                }
            } else {
                $end = "";
            }

            $out = self::white.$this->stripStyles(reset($split)).$out.$end.self::normal;
        } else {
            $out = $this->stripStyles($string);
        }

        $this->ansiOut($out, $newline);
    }

    /**
     * Outoyt brute text.
     *
     * @param string $msg
     * @param boolean $newline
     *
     */
    protected function ansiOut($msg, $newline)
    {
        $nl = "";
        if ($newline) {
            $nl = "\n";
        }
        echo $msg.$nl;
    }

    /**
     * Strip styles from a string.
     *
     * @param $string
     *
     * @return mixed
     */
    protected function stripStyles($string)
    {
        return preg_replace('/(\$[wnoitsgz><]|\$[lh]\[.+\]|\$[lh]|\$[0-9a-f]{3})+/i', '', $string);
    }


    protected function doHslConvert($r, $g, $b)
    {
        $hsl = ColorConversion::rgbToHsl($r, $g, $b);

        $lightness = 100 * $hsl[2];

        $color = "37";
        // if color has saturation
        if ($hsl[1] > 0) {
            $h = $hsl[0];
            if ($h < 20) {
                $color = "31";
            } else {
                if ($h < 70) {
                    $color = "33"; // yellow
                } else {
                    if ($h < 160) {
                        $color = "32"; // green
                    } else {
                        if ($h < 214) {
                            $color = "36"; // cyan
                        } else {
                            if ($h < 284) {
                                $color = "34"; // blue
                            } else {
                                if ($h < 333) {
                                    $color = "35"; // magenta
                                } else {
                                    $color = "31"; // red
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($lightness < 10) {
            $attr = "1";
            $color = "30";
        } else {
            if ($lightness < 44) {
                $attr = "0";
            } else {
                if ($lightness < 95) {
                    $attr = "1";
                } else {
                    $color = "37";
                    $attr = "1";
                }
            }
        }

        return "\e[".$attr.";".$color."m";
    }

    /**
     * Get symphony console.
     *
     * @return OutputInterface
     */
    public function getConsoleOutput()
    {
        if (is_null($this->consoleOutput)) {
            $this->consoleOutput = new NullOutput();
        }

        return $this->consoleOutput;
    }
}
