<?php

namespace eXpansion\Framework\Core\Helpers;

use League\ISO3166\Exception\OutOfBoundsException;
use League\ISO3166\ISO3166;
use Psr\Log\LoggerInterface;

class Countries
{
    /** @var LoggerInterface */
    protected $logger;

    protected $iso;

    /** @var array  */
    protected $countriesMapping = [];

    /** @var string */
    protected $otherCountryCode;

    /** @var string */
    protected $otherCountryLabel;

    /**
     * Countries constructor.
     *
     * @param LoggerInterface $logger
     * @param array           $countriesMapping
     * @param string          $otherCountryCode
     * @param string          $otherCountryLabel
     */
    public function __construct(
        LoggerInterface $logger,
        array $countriesMapping,
        string $otherCountryCode,
        string $otherCountryLabel
    ) {
        $this->logger = $logger;
        $this->countriesMapping = $countriesMapping;
        $this->otherCountryCode = $otherCountryCode;
        $this->otherCountryLabel = $otherCountryLabel;

        $this->iso = new ISO3166();
        $contries = $this->iso->all();
        $contries[] = [
            'name' => 'Chinese Taipei',
            'alpha2' => 'CT',
            'alpha3' => 'TPE',
        ];

        $this->iso = new ISO3166($contries);
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
        if (count($parts) >= 2) {
            return $parts[2];
        }

        return "Other";
    }

    /**
     * Get iso2 code from Maniaplanet Country name
     *
     * @param $name
     *
     * @return string
     */
    public function getIsoAlpha2FromName($name)
    {
        // First try and fetch from alpha3 code.
        try {
            return $this->iso->alpha3($this->getCodeFromCountry($name))['alpha2'];
        } catch (OutOfBoundsException $e) {
            // Nothing code continues.
        }

        // Couldn't getch alpha3 from code try from country name.
        try {
            return $this->iso->name($name)['alpha2'];
        } catch (OutOfBoundsException $e) {
            $this->logger->warning("Can't get valid alpha2 code for country '$name'");
        }

        return "OT";
    }
}
