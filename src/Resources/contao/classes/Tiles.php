<?php

/*
 * @copyright  trilobit GmbH
 * @author     trilobit GmbH <https://github.com/trilobit-gmbh>
 * @license    LGPL-3.0-or-later
 * @link       http://github.com/trilobit-gmbh/contao-tiles-bundle
 */

namespace Trilobit\TilesBundle;

use Contao\Controller;
use Contao\Database;
use Contao\Environment;
use Contao\FilesModel;
use Contao\Frontend;
use Contao\LayoutModel;
use Contao\PageModel;
use Contao\PageRegular;
use Contao\StringUtil;
use Contao\System;
use PHP_ICO;

/**
 * Class Tiles.
 */
class Tiles extends Frontend
{
    /**
     * @param PageModel   $objPage
     * @param LayoutModel $objLayout
     * @param PageRegular $objPageRegular
     */
    public function generatePageHook(PageModel $objPage, LayoutModel $objLayout, PageRegular $objPageRegular)
    {
        global $objPage;

        $arrTiles = [];
        $time = time();

        $strQuery = "(start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1 ";

        $objElements = Database::getInstance()
            ->prepare('SELECT * FROM tl_tiles WHERE '.$strQuery.'ORDER by sorting ASC')
            ->execute();

        if (null === $objElements) {
            return;
        }

        //$arrSettings = Helper::getConfigData();
        $arrData = [];

        //$static = (TL_FILES_URL !== '' ? TL_FILES_URL : Environment::get('url').'/');
        $static = (TL_FILES_URL !== '' ? TL_FILES_URL : '/');

        while ($objElements->next()) {
            $intLastUpdate = $objElements->tstamp;
            $arrPages = StringUtil::deserialize($objElements->pages, true);

            $objTarget = FilesModel::findByPk($objElements->target);

            foreach ($arrPages as $intPageId) {
                $arrData[$intPageId] = self::prepareData($objElements);
                $arrData[$intPageId]['target'] = $static.$objTarget->path;
            }
        }

        if (isset($arrTiles[$objPage->id])) {
            self::processData($arrData[$objPage->id], $intLastUpdate);
        } else {
            $objParentPages = PageModel::findParentsById($objPage->id);

            if (null !== $objParentPages) {
                while ($objParentPages->next()) {
                    if (isset($arrData[$objParentPages->id])) {
                        self::processData($arrData[$objParentPages->id], $intLastUpdate);
                        break;
                    }
                }
            }
        }
    }

    /**
     * @param array $arrData
     * @param mixed $intLastUpdate
     */
    public function processData($arrData = [], $intLastUpdate = '')
    {
        $arrSettings = Helper::getConfigData();
        $arrSettings['system']['lastupdate'] = $intLastUpdate;

        self::processImageData($arrData, $arrSettings);

        self::processAdditionalData($arrData, $arrSettings);

        self::processFaviconData($arrData, $arrSettings);
    }

    /**
     * @param null $objData
     *
     * @return string|void
     */
    public static function previewIcons($objData = null)
    {
        $objImage = FilesModel::findByUuid($objData->singleSRC);
        $objTarget = FilesModel::findByPk($objData->target);

        if (null === $objImage || null === $objTarget) {
            return '';
        }
        $strSourceFile = $objImage->path;
        $strDestinationPath = $objTarget->path;

        $arrSettings = Helper::getConfigData();

        $strReturn = '';

        if (!\is_array($arrSettings['images'])) {
            return;
        }

        foreach ($arrSettings['images'] as $strGroupKey => $value) {
            $strReturn .= '<div class="clr widget">'
                .'<h3><label>'
                .(isset($GLOBALS['TL_LANG']['tl_tiles']['template'][$strGroupKey]) ? $GLOBALS['TL_LANG']['tl_tiles']['template'][$strGroupKey] : '__'.$strGroupKey.'__')
                .'</label></h3>'
                .'</div>'
                ;

            foreach ($value['sizes'] as $strSizesKey => $arrSizesValue) {
                $strFilename = self::getFilename(
                    $strDestinationPath, $value['filename'], $arrSizesValue['filename'], $value['extension'],
                    $objData->alias,
                    $arrSettings['system']['junction'],
                    $arrSizesValue['width'], $arrSizesValue['height']
                );

                $strTitle = self::getFilename(
                    '', $value['filename'], $arrSizesValue['filename'], $value['extension'],
                    $objData->alias,
                    $arrSettings['system']['junction'],
                    $arrSizesValue['width'], $arrSizesValue['height']
                );

                if (is_file(TL_ROOT.'/'.$strFilename)) {
                    $strReturn .= '<div class="widget preview">'
                        .'<div class="image-container">'
                            .'<span>'
                                .'<img src="'.$strFilename.'?t='.time().'" width="'.$arrSizesValue['width'].'" height="'.$arrSizesValue['height'].'">'
                            .'</span>'
                        .'</div>'

                        .'<br>'

                        .'<table class="tl_show">'
                            .'<tbody>'
                                .'<tr>'
                                    .'<td class="tl_bg"><span class="tl_label">ID: </span></td>'
                                    .'<td class="tl_bg">'.substr($strTitle, 1).'</td>'
                                .'</tr>'
                                .'<tr>'
                                    .'<td class=""><span class="tl_label">width: </span></td>'
                                    .'<td class="">'.$arrSizesValue['width'].'</td>'
                                .'</tr>'
                                .'<tr>'
                                    .'<td class="tl_bg"><span class="tl_label">height: </span></td>'
                                    .'<td class="tl_bg">'.$arrSizesValue['height'].'</td>'
                                .'</tr>'
                            .'</tbody>'
                        .'</table>'

                        .'</div>'
                    ;
                }
            }
        }

        return $strReturn;
    }

    /**
     * @param null $objData
     *
     * @throws \Exception
     */
    public function generateTiles($objData = null)
    {
        $objImage = FilesModel::findByUuid($objData->singleSRC);
        $objTarget = FilesModel::findByPk($objData->target);

        if (null === $objImage || null === $objTarget) {
            return;
        }
        $strSourceFile = TL_ROOT.'/'.$objImage->path;
        $strDestinationPath = TL_ROOT.'/'.$objTarget->path;

        $arrSettings = Helper::getConfigData();
        $arrData = self::prepareData($objData);

        $arrJson = [];

        // generate tiles
        foreach ($arrSettings['images'] as $strGroupKey => $value) {
            foreach ($value['sizes'] as $strSizesKey => $arrSizesValue) {
                $strFilename = self::getFilename(
                    $strDestinationPath, $value['filename'], $arrSizesValue['filename'], $value['extension'],
                    $objData->alias,
                    $arrSettings['system']['junction'],
                    $arrSizesValue['width'], $arrSizesValue['height']
                );

                System::getContainer()
                    ->get('contao.image.image_factory')
                    ->create(
                        $strSourceFile,
                        [
                            $arrSizesValue['width'],
                            $arrSizesValue['height'],
                            $objData->crop,
                        ],
                        $strFilename
                    )
                    ->getUrl(TL_ROOT);
            }
        }

        // generate webApp options
        if (!empty($arrSettings['webApp']['options'])) {
            foreach ($arrSettings['webApp']['options'] as $value) {
                $strName = $value['name'];

                $arrJson[$strName] = self::getCurrentData($arrData, $value['content']);
            }
        }

        // generate webApp settings
        if (!empty($arrSettings['webApp'])) {
            foreach ($arrSettings['webApp'] as $key => $value) {
                if (\is_array($value)) {
                    continue;
                }
                $arrSettings['webApp'][$key] = self::getCurrentData($arrData, $value);
            }
        }

        /*
        $strFilename = self::getFilename(
            $strDestinationPath, $arrSettings['webApp']['filename'], $arrSettings['webApp']['filename'], $arrSettings['webApp']['extension'],
            $arrData['alias'],
            $arrSettings['system']['junction']
        );

        $fileHandler = fopen($strFilename, 'w');
        fwrite($fileHandler, json_encode($arrJson));
        fclose($fileHandler);
        */

        // generate favicon
        $strFaviconSourceForIcoFilename = $arrSettings['favicon']['source'];
        $strFaviconSourceForIcoFilename = str_replace('##target##', $objTarget->path, $strFaviconSourceForIcoFilename);
        $strFaviconSourceForIcoFilename = str_replace('##alias##', $objData->alias, $strFaviconSourceForIcoFilename);
        $strFaviconSourceForIcoFilename = str_replace('##junction##', $arrSettings['system']['junction'], $strFaviconSourceForIcoFilename);

        self::createIcoFile(
            TL_ROOT.'/'.$strFaviconSourceForIcoFilename,
            $objTarget->path.'/'.$arrSettings['favicon']['filename'].'.'.$arrSettings['favicon']['extension'],
            $arrSettings['favicon']['sizes']
        );

        Database::getInstance()
            ->prepare('UPDATE tl_tiles SET forceUpdate=? WHERE id=?')
            ->limit(1)
            ->execute('', $objData->id);
    }

    /**
     * @param array $arrData
     * @param array $arrSettings
     */
    protected function processImageData($arrData = [], $arrSettings = [])
    {
        if (!\is_array($arrSettings['images'])) {
            return;
        }
        foreach ($arrSettings['images'] as $key => $value) {
            self::addToHeader('<!-- images::'.$key.' -->');

            foreach ($value['sizes'] as $strSizesKey => $arrSizesValue) {
                $strHeader = '<'.$value['tag'];

                foreach ($value['attributes'] as $aKey => $aValue) {
                    if ('' === $aValue) {
                        $strHeader .= ' '.$aKey;
                    } else {
                        $strHeader .= ' '.$aKey.'="'.$aValue.'"';
                    }
                }

                if (isset($arrSizesValue['media'])) {
                    $strHeader .= ' media="'.$arrSizesValue['media'].'"';
                }

                $strHeader .= '>';

                // update information
                foreach ($arrData as $dKey => $dValue) {
                    $strHeader = str_replace('##'.$dKey.'##', $dValue, $strHeader);
                }

                $strHeader = str_replace('##extension##', $value['extension'], $strHeader);

                $strHeader = str_replace('##filename##', isset($arrSizesValue['filename']) ? $arrSizesValue['filename'] : $value['filename'], $strHeader);
                $strHeader = str_replace('##name##', $arrSizesValue['name'], $strHeader);
                $strHeader = str_replace('##width##', $arrSizesValue['width'], $strHeader);
                $strHeader = str_replace('##height##', $arrSizesValue['height'], $strHeader);
                $strHeader = str_replace('##lastupdate##', $arrSettings['system']['lastupdate'], $strHeader);

                $strHeader = preg_replace('/="\/http/', '="http', $strHeader);

                // add to header
                self::addToHeader($strHeader);
            }
        }
    }

    /**
     * @param array $arrData
     * @param array $arrSettings
     */
    protected function processAdditionalData($arrData = [], $arrSettings = [])
    {
        if (!\is_array($arrSettings['additionals'])) {
            return;
        }
        foreach ($arrSettings['additionals'] as $key => $value) {
            self::addToHeader('<!-- additionals::'.$key.' -->');

            foreach ($value as $strItemKey => $arrItemValue) {
                if ('' === $arrData[$strItemKey] || null === $arrData[$strItemKey]) {
                    continue;
                }

                $strHeader = '<'.$arrItemValue['tag'].' ';

                foreach ($arrItemValue['attributes'] as $strAttributeKey => $strAttributeValue) {
                    $strHeader .= $strAttributeKey.'="'.$strAttributeValue.'" ';
                }

                $strHeader .= '>';

                foreach ($arrData as $dKey => $dValue) {
                    $strHeader = str_replace('##'.$dKey.'##', $dValue, $strHeader);
                }

                self::addToHeader($strHeader);
            }
        }
    }

    /**
     * @param array $arrData
     * @param array $arrSettings
     */
    protected function processFaviconData($arrData = [], $arrSettings = [])
    {
        if (!\is_array($arrSettings['favicon']['attributes']['integration'])) {
            return;
        }
        self::addToHeader('<!-- favicon -->');

        foreach ($arrSettings['favicon']['attributes']['integration'] as $value) {
            $strHeader = '<'.$arrSettings['favicon']['tag'].' ';

            foreach ($value as $strAttributeKey => $strAttributeValue) {
                $strHeader .= $strAttributeKey.'="'.$strAttributeValue.'" ';
            }

            foreach ($arrSettings['favicon']['attributes'] as $strAttributeKey => $strAttributeValue) {
                if ('integration' === $strAttributeKey) {
                    continue;
                }
                $strHeader .= $strAttributeKey.'="'.$strAttributeValue.'" ';
            }

            $strHeader .= '>';

            foreach ($arrData as $dKey => $dValue) {
                $strHeader = str_replace('##'.$dKey.'##', $dValue, $strHeader);
            }

            $strHeader = str_replace('##extension##', $arrSettings['favicon']['extension'], $strHeader);
            $strHeader = str_replace('##filename##', $arrSettings['favicon']['filename'], $strHeader);
            $strHeader = str_replace('##name##', $arrSettings['favicon']['name'], $strHeader);
            $strHeader = str_replace('##lastupdate##', $arrSettings['system']['lastupdate'], $strHeader);

            self::addToHeader($strHeader);
        }
    }

    /**
     * @param $objData
     *
     * @return array
     */
    protected function prepareData($objData)
    {
        global $objPage;
        
        $arrWebappThemeColor = StringUtil::deserialize($objData->webappThemeColor, true);
        $strWebappThemeColor = $arrWebappThemeColor[0];

        $arrWebappBackgroundColor = StringUtil::deserialize($objData->webappBackgroundColor, true);
        $strWebappBackgroundColor = $arrWebappBackgroundColor[0];

        $arrWindowsTileColor = StringUtil::deserialize($objData->windowsTileColor, true);
        $strWindowsTileColor = $arrWindowsTileColor[0];

        $arrWindowsTooltipColor = StringUtil::deserialize($objData->windowsTooltipColor, true);
        $strWindowsTooltipColor = $arrWindowsTooltipColor[0];

        $arrWindowsSize = StringUtil::deserialize($objData->windowsSize, true);

        $strWindowsRssFrequency = ('' !== $objData->windowsRss ? ('' !== $objData->windowsRssFrequency ? $objData->windowsRssFrequency : 30) : '');
        $strWindowsSize = ('' !== $arrWindowsSize[0] && '' !== $arrWindowsSize[1] ? 'width='.$arrWindowsSize[0].';height='.$arrWindowsSize[1] : '');

        $arrSettings = Helper::getConfigData();

        $arrWindows = [];
        $arrWebapp = [];
        $arrIos = [];
        $arrAndroid = [];

        if ($objData->addWindowstiles) {
            $arrWindows = [
                'windowsTitle' => $objData->windowsTitle,
                'windowsTooltip' => $objData->windowsTooltip,
                'windowsDns' => $objData->windowsDns,
                'windowsSize' => $strWindowsSize,
                'windowsTileColor' => $strWindowsTileColor,
                'windowsTooltipColor' => $strWindowsTooltipColor,
            ];

            if ($objData->addWindowsrss) {
                $arrWindows = array_merge(
                    $arrWindows,
                    [
                        'windowsRss' => $objData->windowsRss,
                        'windowsRssFrequency' => $strWindowsRssFrequency,
                    ]
                );
            }
        }

        if ($objData->addWebapp) {
            $arrWebapp = [
                'webappName' => $objData->webappName,
                'webappShortName' => $objData->webappShortName,
                'webappDescription' => $objData->webappDescription,
                'webappDisplay' => $objData->webappDisplay,
                'webappOrientation' => $objData->webappOrientation,
                'webappThemeColor' => $strWebappThemeColor,
                'webappBackgroundColor' => $strWebappBackgroundColor,
            ];
            
            if (!empty($objPage->description)) {
                unset($arrWebapp['webappDescription']);
            }
        }

        if ($objData->addIos) {
            $arrIos = [
                'iosStatusBarStyle' => $objData->iosStatusBarStyle,
                'iosTitle' => $objData->iosTitle,
                'iosApp' => $objData->iosApp,
            ];
        }

        if ($objData->addAndroid) {
            $arrAndroid = [
                'androidApp' => $objData->androidApp,
            ];
        }

        return array_merge(
            [
                'alias' => $objData->alias,
                'junction' => $arrSettings['system']['junction'],
            ],
            $arrWindows,
            $arrWebapp,
            $arrIos,
            $arrAndroid
        );
    }

    /**
     * @param $strData
     */
    protected function addToHeader($strData)
    {
        $GLOBALS['TL_HEAD'][] = Controller::replaceInsertTags($strData);
    }

    /**
     * @param string $strPath
     * @param string $strFilenameA
     * @param string $strFilenameB
     * @param string $strExtension
     * @param string $strAlias
     * @param string $strJunction
     * @param string $strWidth
     * @param string $strHeight
     *
     * @return mixed|string
     */
    protected static function getFilename($strPath = '', $strFilenameA = '', $strFilenameB = '', $strExtension = '', $strAlias = '', $strJunction = '', $strWidth = '', $strHeight = '')
    {
        //$strData = TL_FILES_URL.$strPath
        $strData = $strPath
            .'/'
            .(isset($strFilenameB) ? $strFilenameB : $strFilenameA)
            .'.'.$strExtension
        ;

        $strData = str_replace('##width##', $strWidth, $strData);
        $strData = str_replace('##height##', $strHeight, $strData);
        $strData = str_replace('##alias##', $strAlias, $strData);
        $strData = str_replace('##junction##', $strJunction, $strData);

        return $strData;
    }

    /**
     * @param $arrData
     * @param $strData
     *
     * @return mixed|string
     */
    protected static function getCurrentData($arrData, $strData)
    {
        foreach ($arrData as $key => $value) {
            $strData = str_replace('##'.$key.'##', $value, $strData);
        }

        $strData = Controller::replaceInsertTags($strData);

        return $strData;
    }

    /**
     * @param $strSourceFile
     * @param $strTargetFile
     * @param $arrFaviconSizes
     *
     * @throws \Exception
     */
    protected function createIcoFile($strSourceFile, $strTargetFile, $arrFaviconSizes)
    {
        $objIconFile = new PHP_ICO($strSourceFile, $arrFaviconSizes);
        $objFile = new \File($strTargetFile);
        $objFile->write($objIconFile->_get_ico_data());
        $objFile->close();
    }
}

/*
 * Configuring Web Applications
 * https://developer.apple.com/library/safari/documentation/appleapplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html
 * Safari Web Content Guide
 *
 * Specifying a Startup Image
 * <link rel="apple-touch-startup-image" href="/startup.png">
 *
 * Hiding Safari User Interface Components
 * <meta name="apple-mobile-web-app-capable" content="yes">
 *
 * Changing the Status Bar Appearance
 * <meta name="apple-mobile-web-app-status-bar-style" content="black">
 *
 */
