<?php

declare(strict_types=1);

/*
 * @copyright  trilobit GmbH
 * @author     trilobit GmbH <https://github.com/trilobit-gmbh>
 * @license    LGPL-3.0-or-later
 */

namespace Trilobit\TilesBundle;

use Contao\Database;
use Contao\DataContainer;
use Contao\System;
use Exception;
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

    public function generateTiles(DataContainer $dc)
    {
        // Return if there is no ID
        if (!$dc->id) {
            return;
        }

        Tiles::generateTiles($dc->activeRecord);
    }

    /**
     * @return string|void
     */
    public function previewIcons(DataContainer $dc)
    {
        // Return if there is no ID
        if (!$dc->id) {
            return;
        }

        return '<div class="tl_box"><div id="tiles_images">'
            .Tiles::previewIcons($dc->activeRecord)
            .'</div></div>'
            ;
    }

    /**
     * @param $varValue
     *
     * @throws Exception
     *
     * @return mixed|string
     */
    public function generateAlias($varValue, DataContainer $dc)
    {
        $aliasExists = static function(string $value): bool {
            return Database::getInstance()
                    ->prepare('SELECT id FROM tl_tiles WHERE alias=?')
                    ->execute($value)
                    ->numRows > 0;
        };

        if ($varValue) {
            return $varValue;
        }

        // Generate the alias if there is none
        if (!$varValue) {
            $varValue = System::getContainer()
                ->get('contao.slug')
                ->generate($dc->activeRecord->name, 0, $aliasExists)
            ;
        } elseif (preg_match('/^[1-9]\d*$/', $varValue)) {
            throw new \Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasNumeric'], $varValue));
        } elseif ($aliasExists($varValue)) {
            throw new \Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
        }

        return $varValue;
    }

    public function getWebAppManifestDisplayOptions(DataContainer $dc): array
    {
        return array_keys(self::getConfigData()['options']['webAppManifest']['display']);
    }

    public function getWebAppManifestOrientationOptions(DataContainer $dc): array
    {
        return array_keys(self::getConfigData()['options']['webAppManifest']['orientation']);
    }

    public function getIosStatusBarStyleOptions(DataContainer $dc): array
    {
        return array_keys(self::getConfigData()['options']['ios']['statusBar']);
    }
}
