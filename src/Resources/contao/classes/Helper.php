<?php

declare(strict_types=1);

/*
 * @copyright  trilobit GmbH
 * @author     trilobit GmbH <https://github.com/trilobit-gmbh>
 * @license    LGPL-3.0-or-later
 */

namespace Trilobit\TilesBundle;

use Symfony\Component\Yaml\Yaml;

/**
 * Class Helper.
 */
class Helper
{
    /**
     * @return string
     */
    public static function getVendowDir()
    {
        return \dirname(__DIR__);
    }

    /**
     * @return mixed
     */
    public static function getConfigData()
    {
        $strYml = file_get_contents(self::getVendowDir().'/../config/config.yml');

        return Yaml::parse($strYml)['trilobit']['tiles'];
    }
}
