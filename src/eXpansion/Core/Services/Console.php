<?php

namespace eXpansion\Core\Services;

use Symfony\Component\Console\Output\ConsoleOutputInterface;

/**
 * Class Console to print in the console.
 *
 * @package eXpansion\Core\Services
 * @author Reaby
 */
class Console
{

    const black = "\e[0;30m";
    const b_black = "\e[30;1m";

    const red = "\e[0;31m";
    const b_red = "\e[31;1m";

    const green = "\e[0;32m";
    const b_green = "\e[32;1m";

    const yellow = "\e[0;33m";
    const b_yellow = "\e[33;1m";

    const blue = "\e[0;34m";
    const b_blue = "\e[34;1m";

    const magenta = "\e[0;35m";
    const b_magenta = "\e[35;1m";

    const cyan = "\e[0;36m";
    const b_cyan = "\e[36;1m";

    const white = "\e[0;37m";
    const b_white = "\e[37;1m";

    // define aliases for colors
    const error = "\e[37;1m\e[41m";
    const success = self::b_green;
    const normal = self::white;
    const bold = self::b_white;


    /** @var ConsoleOutputInterface */
    protected $consoleOutput;

    /** @var boolean Color console enabled */
    protected $colorEnabled;

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
     * @param ConsoleOutputInterface $consoleOutput
     */
    public function init(ConsoleOutputInterface $consoleOutput)
    {
        $this->consoleOutput = $consoleOutput;
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
        if ($this->colorEnabled && $this->consoleOutput->isDecorated()) {
            $array = array("000" => self::b_black,
                "100" => self::red,
                "010" => self::green,
                "110" => self::yellow,
                "001" => self::blue,
                "011" => self::magenta,
                "101" => self::cyan,
                "111" => self::white,
                "200" => self::b_red,
                "211" => self::red,
                "121" => self::green,
                "020" => self::b_green,
                "021" => self::green,
                "012" => self::cyan,
                "221" => self::b_yellow,
                "220" => self::b_yellow,
                "120" => self::green,
                "210" => self::yellow,
                "112" => self::b_blue,
                "002" => self::b_blue,
                "122" => self::b_cyan,
                "022" => self::b_cyan,
                "202" => self::b_magenta,
                "212" => self::b_magenta,
                "102" => self::magenta,
                "201" => self::b_red,
                "222" => self::b_white,
            );
            $matches = array();
            preg_match_all("/\\$[A-Fa-f0-9]{3}/", $string, $matches);
            $split = preg_split("/\\$[A-Fa-f0-9]{3}/", $string);

            $out = "";
            foreach ($matches[0] as $i => $rgb) {
                $code = $this->fixColors(hexdec($rgb[1]), hexdec($rgb[2]), hexdec($rgb[3]));
                if (array_key_exists($code, $array)) {
                    $out .= $array[$code] . $this->stripStyles($split[$i + 1]);
                } else {
                    $out .= self::white . $this->stripStyles($split[$i + 1]);
                }
                $end = $this->stripStyles($split[$i + 1]);
            }


            if (!empty($end)) {
                if ($end == $this->stripStyles(end($split))) {
                    $end = "";
                }
            } else {
                $end = "";
            }

            $out = self::white . $this->stripStyles(reset($split)) . $out . $end;
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
        $this->consoleOutput->write($msg, $newline, ConsoleOutputInterface::OUTPUT_RAW);
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
        $string = preg_replace('/(?<!\$)((?:\$\$)*)\$[^$0-9a-hlp]/iu', '$1', $string);
        return preg_replace('/(?<!\$)((?:\$\$)*)\$(?:g|[0-9a-f][^\$]{0,2})/iu', '$1', $string);
    }

    /**
     * Fix the color codes from MP standard to world standard
     *
     * @param string $r
     * @param string $g
     * @param string $b
     *
     * @return string
     */
    public function fixColors($r, $g, $b)
    {
        $out = "111";
        // black/gray/white
        if ($r == $g && $g == $b && $b == $r) {
            if ($r >= 0 && $r < 5) {
                $out = "000";
            }
            if ($r >= 5 && $r < 13) {
                $out = "111";
            }
            if ($r >= 13 && $r <= 16) {
                $out = "222";
            }
        } else {
            $out = $this->convert($r) . $this->convert($g) . $this->convert($b);
        }
        return $out;
    }

    /**
     * Convert.
     *
     * @param int $number
     *
     * @return string
     */
    public function convert($number)
    {
        $out = "0";

        if ($number >= 9 && $number <= 16) {
            $out = "2";
        }
        if ($number >= 3 && $number < 9) {
            $out = "1";
        }
        if ($number >= 0 && $number < 3) {
            $out = "0";
        }
        return $out;
    }
}
