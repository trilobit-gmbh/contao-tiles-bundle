<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Trilobit\TilesBundle;

use Symfony\Component\Yaml\Yaml;


/**
 * Class Helper
 * @package Trilobit\TilesBundle
 */
class Helper
{

    /**
     * @return string
     */
    public static function getVendowDir()
    {
        return dirname(dirname(__FILE__));
    }


    /**
     * @return mixed
     */
    public static function getConfigData()
    {
        $strYml = file_get_contents(self::getVendowDir() . '/../config/config.yml');

        return Yaml::parse($strYml)['trilobit']['tiles'];
    }
}
