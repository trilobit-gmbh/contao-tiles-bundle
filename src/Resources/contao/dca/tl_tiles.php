<?php

/*
 * @copyright  trilobit GmbH
 * @author     trilobit GmbH <https://github.com/trilobit-gmbh>
 * @license    LGPL-3.0-or-later
 * @link       http://github.com/trilobit-gmbh/contao-tiles-bundle
 */

System::loadLanguageFile('tl_content');
System::loadLanguageFile('tl_article');

/*
 * Table tl_tiles
 */
$GLOBALS['TL_DCA']['tl_tiles'] = [
    // Config
    'config' => [
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'switchToEdit' => true,
        'onsubmit_callback' => [
            ['tl_tiles', 'generateTiles'],
        ],
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'pid' => 'index',
            ],
        ],
    ],

    // List
    'list' => [
        'sorting' => [
            'mode' => 1,
            'fields' => ['name'],
            'flag' => 1,
            'panelLayout' => 'search,limit',
        ],
        'label' => [
            'fields' => ['name'],
            'format' => '%s',
        ],
        'global_operations' => [
            'all' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
        ],
        'operations' => [
            'edit' => [
                'label' => &$GLOBALS['TL_LANG']['tl_tiles']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.gif',
            ],
            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_tiles']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.gif',
            ],
            'cut' => [
                'label' => &$GLOBALS['TL_LANG']['tl_tiles']['cut'],
                'href' => 'act=paste&amp;mode=cut',
                'icon' => 'cut.gif',
            ],
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_tiles']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if (!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\')) return false; Backend.getScrollOffset();"',
            ],
            'toggle' => [
                'label' => &$GLOBALS['TL_LANG']['tl_tiles']['toggle'],
                'icon' => 'visible.gif',
                'attributes' => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => ['tl_tiles', 'toggleIcon'],
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_tiles']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif',
            ],
        ],
    ],

    // Palettes
    'palettes' => [
        '__selector__' => [
            'addWindowstiles',
            'addWindowsrss',
            'addIos',
            'addAndroid',
            'addWebapp',
        ],
        'default' => '{title_legend},name,alias;'
                   .'{destination_legend:hide},pages,target;'
                   .'{webapp_legend},addWebapp;'
                   .'{windows_legend:hide},addWindowstiles;'
                   .'{ios_legend:hide},addIos;'
                   .'{android_legend:hide},addAndroid;'
                   .'{favicon_legend:hide},singleSRC,crop,forceUpdate;'
                   .'{preview_legend:hide},preview;'
                   .'{publish_legend:hide},published,start,stop',
    ],

    // Subpalettes
    'subpalettes' => [
        'addWebapp' => 'webappName,webappShortName,webappDescription,webappDisplay,webappOrientation,webappThemeColor,webappBackgroundColor',
        'addWindowstiles' => 'windowsTitle,windowsTooltip,windowsDns,addWindowsrss,windowsTileColor,windowsTooltipColor,windowsSize',
        'addWindowsrss' => 'windowsRss,windowsRssFrequency',
        'addIos' => 'iosTitle,iosStatusBarStyle,iosApp',
        'addAndroid' => 'androidApp',
    ],

    // Fields
    'fields' => [
        'name' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['name'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'alias' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['alias'],
            'exclude' => true,
            'search' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'alias', 'unique' => true, 'maxlength' => 128, 'tl_class' => 'w50'],
            'save_callback' => [
                ['tl_tiles', 'generateAlias'],
            ],
            'sql' => "varbinary(128) NOT NULL default ''",
        ],
        'pages' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['pages'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'pageTree',
            'eval' => ['multiple' => true, 'fieldType' => 'checkbox', 'mandatory' => true],
            'sql' => 'blob NULL',
        ],
        'target' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['target'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'fileTree',
            'eval' => ['multiple' => false, 'fieldType' => 'radio', 'mandatory' => true],
            'sql' => 'blob NULL',
        ],
        'singleSRC' => [
            'label' => &$GLOBALS['TL_LANG']['tl_content']['singleSRC'],
            'exclude' => true,
            'inputType' => 'fileTree',
            'eval' => ['mandatory' => true, 'filesOnly' => true, 'fieldType' => 'radio', 'extensions' => 'jpg,jpeg,png,gif'],
            'sql' => 'binary(16) NULL',
        ],
        'forceUpdate' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['forceUpdate'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'checkbox',
            'eval' => ['doNotCopy' => true, 'tl_class' => 'w50 m12'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'crop' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['crop'],
            'exclude' => true,
            'inputType' => 'select',
            'options' => $GLOBALS['TL_CROP'],
            'reference' => &$GLOBALS['TL_LANG']['MSC'],
            'eval' => ['helpwizard' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],

        'addWebapp' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['addWebapp'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['submitOnChange' => true, 'tl_class' => 'clr'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'webappName' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['webappName'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'webappShortName' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['webappShortName'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'webappDescription' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['webappDescription'],
            'exclude' => true,
            'inputType' => 'textarea',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'clr'],
            'sql' => "text NOT NULL default ''",
        ],
        'webappDisplay' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['webappDisplay'],
            'exclude' => true,
            'inputType' => 'select',
            'options_callback' => ['tl_tiles', 'getWebAppManifestDisplayOptions'],
            'reference' => &$GLOBALS['TL_LANG']['tl_tiles']['options']['webappDisplay'],
            'eval' => ['chosen' => true, 'includeBlankOption' => true, 'tl_class' => 'clr w50'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'webappOrientation' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['webappOrientation'],
            'exclude' => true,
            'inputType' => 'select',
            'options_callback' => ['tl_tiles', 'getWebAppManifestOrientationOptions'],
            'reference' => &$GLOBALS['TL_LANG']['tl_tiles']['options']['webappOrientation'],
            'eval' => ['chosen' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'webappThemeColor' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['webappThemeColor'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 6, 'multiple' => true, 'size' => 2, 'colorpicker' => true, 'isHexColor' => true, 'decodeEntities' => true, 'tl_class' => 'clr w50 wizard'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'webappBackgroundColor' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['webappBackgroundColor'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 6, 'multiple' => true, 'size' => 2, 'colorpicker' => true, 'isHexColor' => true, 'decodeEntities' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],

        'addWindowstiles' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['addWindowstiles'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['submitOnChange' => true, 'tl_class' => 'clr'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'windowsTitle' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['windowsTitle'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'windowsTooltip' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['windowsTooltip'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'windowsDns' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['windowsDns'],
            'exclude' => true,
            'inputType' => 'text',
            'search' => true,
            'eval' => ['rgxp' => 'url', 'decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'clr'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'addWindowsrss' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['addWindowsrss'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['submitOnChange' => true, 'tl_class' => 'clr'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'windowsRss' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['windowsRss'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'url', 'decodeEntities' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'windowsRssFrequency' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['windowsRssFrequency'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'digit', 'decodeEntities' => true, 'tl_class' => 'w50'],
            'sql' => "int(10) NOT NULL default '30'",
        ],
        'windowsTileColor' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['windowsTileColor'],
            'inputType' => 'text',
            'eval' => ['maxlength' => 6, 'multiple' => true, 'size' => 2, 'colorpicker' => true, 'isHexColor' => true, 'decodeEntities' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'windowsTooltipColor' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['windowsTooltipColor'],
            'inputType' => 'text',
            'eval' => ['maxlength' => 6, 'multiple' => true, 'size' => 2, 'colorpicker' => true, 'isHexColor' => true, 'decodeEntities' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'windowsSize' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['windowsSize'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['multiple' => true, 'size' => 2, 'rgxp' => 'digit', 'nospace' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],

        'addIos' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['addIos'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['submitOnChange' => true, 'tl_class' => 'clr'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'iosTitle' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['iosTitle'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'iosApp' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['iosApp'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'checkbox',
            'eval' => ['doNotCopy' => true, 'tl_class' => 'w50 m12'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'iosStatusBarStyle' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['iosStatusBarStyle'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'select',
            'options_callback' => ['tl_tiles', 'getIosStatusBarStyleOptions'],
            'reference' => &$GLOBALS['TL_LANG']['tl_tiles']['options']['iosStatusBarStyle'],
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(32) NOT NULL default ''",
        ],

        'addAndroid' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['addAndroid'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['submitOnChange' => true, 'tl_class' => 'clr'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'androidApp' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['androidApp'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'checkbox',
            'eval' => ['doNotCopy' => true, 'tl_class' => 'w50 m12'],
            'sql' => "char(1) NOT NULL default ''",
        ],

        'preview' => [
            'input_field_callback' => ['tl_tiles', 'previewIcons'],
            'eval' => ['doNotShow' => true],
        ],
        'published' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['published'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'checkbox',
            'eval' => ['doNotCopy' => true],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'start' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['start'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
        'stop' => [
            'label' => &$GLOBALS['TL_LANG']['tl_tiles']['stop'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'pid' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'sorting' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
    ],
];

/**
 * Class tl_tiles.
 */
class tl_tiles extends Backend
{
    /**
     * tl_tiles constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    /**
     * @param DataContainer $dc
     */
    public function generateTiles(DataContainer $dc)
    {
        // Return if there is no ID
        if (!$dc->id) {
            return;
        }

        \Trilobit\TilesBundle\Tiles::generateTiles($dc->activeRecord);
    }

    /**
     * @param DataContainer $dc
     *
     * @return string|void
     */
    public function previewIcons(\DataContainer $dc)
    {
        // Return if there is no ID
        if (!$dc->id) {
            return;
        }

        return '<div class="tl_box"><div id="tiles_images">'
            .\Trilobit\TilesBundle\Tiles::previewIcons($dc->activeRecord)
            .'</div></div>'
            ;
    }

    /**
     * @param $varValue
     * @param DataContainer $dc
     *
     * @throws Exception
     *
     * @return mixed|string
     */
    public function generateAlias($varValue, DataContainer $dc)
    {
        $autoAlias = false;

        // Generate alias if there is none
        if ('' === $varValue) {
            $autoAlias = true;
            $varValue = standardize(StringUtil::restoreBasicEntities($dc->activeRecord->name));
        }

        $objAlias = $this->Database->prepare('SELECT id FROM tl_tiles WHERE alias=?')
            ->execute($varValue);

        // Check whether the news alias exists
        if ($objAlias->numRows > 1 && !$autoAlias) {
            throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
        }

        // Add ID to alias
        if ($objAlias->numRows && $autoAlias) {
            $varValue .= '-'.$dc->id;
        }

        return $varValue;
    }

    /**
     * @param $row
     * @param $href
     * @param $label
     * @param $title
     * @param $icon
     * @param $attributes
     *
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (\strlen(Input::get('tid'))) {
            $this->toggleVisibility(Input::get('tid'), (1 === Input::get('state')));
            $this->redirect($this->getReferer());
        }

        $href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

        if (!$row['published']) {
            $icon = 'invisible.gif';
        }

        return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
    }

    /**
     * @param $intId
     * @param $blnVisible
     */
    public function toggleVisibility($intId, $blnVisible)
    {
        // Check permissions to edit
        Input::setGet('id', $intId);
        Input::setGet('act', 'toggle');

        $objVersions = new Versions('tl_tiles', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (\is_array($GLOBALS['TL_DCA']['tl_tiles']['fields']['published']['save_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_tiles']['fields']['published']['save_callback'] as $callback) {
                $this->import($callback[0]);
                $blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
            }
        }

        // Update the database
        $this->Database->prepare('UPDATE tl_tiles SET tstamp='.time().", published='".($blnVisible ? 1 : '')."' WHERE id=?")
            ->execute($intId);

        $objVersions->create();

        $strText = 'A new version of record "tl_tiles.id='.$intId.'" has been created '.$this->getParentEntries('tl_tiles', $intId);
        $strFunction = 'tl_tiles toggleVisibility()';
        $strCategory = TL_GENERAL;

        $level = TL_ERROR === $strCategory ? LogLevel::ERROR : LogLevel::INFO;
        $logger = static::getContainer()->get('monolog.logger.contao');

        $logger->log($level, $strText, ['contao' => new ContaoContext($strFunction, $strCategory)]);
    }

    /**
     * @param DataContainer $dc
     *
     * @return array
     */
    public function getWebAppManifestDisplayOptions(DataContainer $dc)
    {
        return array_keys(\Trilobit\TilesBundle\Helper::getConfigData()['options']['webAppManifest']['display']);
    }

    /**
     * @param DataContainer $dc
     *
     * @return array
     */
    public function getWebAppManifestOrientationOptions(DataContainer $dc)
    {
        return array_keys(\Trilobit\TilesBundle\Helper::getConfigData()['options']['webAppManifest']['orientation']);
    }

    /**
     * @param DataContainer $dc
     *
     * @return array
     */
    public function getIosStatusBarStyleOptions(DataContainer $dc)
    {
        return array_keys(\Trilobit\TilesBundle\Helper::getConfigData()['options']['ios']['statusBar']);
    }
}
