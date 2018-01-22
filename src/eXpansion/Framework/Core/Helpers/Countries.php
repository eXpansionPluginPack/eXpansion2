<?php

namespace eXpansion\Framework\Core\Helpers;

class Countries
{
    /** @var array  */
    protected $countriesMapping = [];

    /** @var string */
    protected $otherCountryCode;

    /** @var string */
    protected $otherCountryLabel;

    /**
     * Countries constructor.
     *
     * @param array $countriesMapping
     * @param string $otherCountryCode
     * @param string $otherCountryLabel
     */
    public function __construct(array $countriesMapping, string $otherCountryCode, string $otherCountryLabel)
    {
        $this->countriesMapping = $countriesMapping;
        $this->otherCountryCode = $otherCountryCode;
        $this->otherCountryLabel = $otherCountryLabel;
    }


    /**
     * Get 3-letter country code from full country name
     *
     * @param $country
     * @return mixed|string
     */
    public function getCodeFromCountry($country)
    {
        $output = 'OTH';
        if (array_key_exists($country, $this->countriesMapping)) {
            $output = $this->countriesMapping[$country];
        }

        return $output;
    }

    /**
     * Get full country name from 3-letter country code
     *
     * @param string $code
     * @return string
     */
    public function getCountryFromCode($code)
    {
        $code = strtoupper($code);
        $output = "Other";
        if (in_array($code, $this->countriesMapping)) {
            foreach ($this->countriesMapping as $country => $short) {
                if ($code == $short) {
                    $output = $country;
                    break;
                }
            }
        }

        return $output;
    }

    /**
     * Parses country from maniaplanet player objects' path
     *
     * @param string $path Maniaplanet path from player object
     * @return string long country name
     */
    public function parseCountryFromPath($path)
    {
        $parts = explode("|", $path);
        if (count($parts)>= 2) {
            return $parts[2];
        }

        return "Other";
    }
}
