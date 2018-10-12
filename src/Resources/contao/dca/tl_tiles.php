<?php

/**
 * Load language file(s)
 */
System::loadLanguageFile('tl_content');
System::loadLanguageFile('tl_article');


/**
 * Table tl_tiles
 */
$GLOBALS['TL_DCA']['tl_tiles'] = array
(
    // Config
    'config' => array
    (
        'dataContainer'            => 'Table',
        'enableVersioning'         => true,
        'switchToEdit'             => true,
        'onsubmit_callback'        => array
        (
            array('tl_tiles', 'generateTiles')
        ),
        'sql' => array
        (
            'keys' => array
            (
                'id'               => 'primary',
                'pid'              => 'index'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                 => 1,
            'fields'               => array('name'),
            'flag'                 => 1,
            'panelLayout'          => 'search,limit',
        ),
        'label' => array
        (
            'fields'               => array('name'),
            'format'               => '%s',
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'            => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'             => 'act=select',
                'class'            => 'header_edit_all',
                'attributes'       => 'onclick="Backend.getScrollOffset();"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'            => &$GLOBALS['TL_LANG']['tl_tiles']['edit'],
                'href'             => 'act=edit',
                'icon'             => 'edit.gif'
            ),
            'copy' => array
            (
                'label'            => &$GLOBALS['TL_LANG']['tl_tiles']['copy'],
                'href'             => 'act=copy',
                'icon'             => 'copy.gif'
            ),
            'cut' => array
            (
                'label'            => &$GLOBALS['TL_LANG']['tl_tiles']['cut'],
                'href'             => 'act=paste&amp;mode=cut',
                'icon'             => 'cut.gif'
            ),
            'delete' => array
            (
                'label'            => &$GLOBALS['TL_LANG']['tl_tiles']['delete'],
                'href'             => 'act=delete',
                'icon'             => 'delete.gif',
                'attributes'       => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'toggle' => array
            (
                'label'            => &$GLOBALS['TL_LANG']['tl_tiles']['toggle'],
                'icon'             => 'visible.gif',
                'attributes'       => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback'  => array('tl_tiles', 'toggleIcon')
            ),
            'show' => array
            (
                'label'            => &$GLOBALS['TL_LANG']['tl_tiles']['show'],
                'href'             => 'act=show',
                'icon'             => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__' => array
        (
            'addWindowstiles',
            'addWindowsrss',
            'addIos',
            'addAndroid',
            'addWebapp',
        ),
        'default' => '{title_legend},name,alias;'
                   . '{destination_legend:hide},pages,target;'
                   . '{webapp_legend},addWebapp;'
                   . '{windows_legend:hide},addWindowstiles;'
                   . '{ios_legend:hide},addIos;'
                   . '{android_legend:hide},addAndroid;'
                   . '{favicon_legend:hide},singleSRC,crop,forceUpdate;'
                   . '{preview_legend:hide},preview;'
                   . '{publish_legend:hide},published,start,stop',
    ),

    // Subpalettes
    'subpalettes' => array
    (
        'addWebapp'       => 'webappName,webappShortName,webappDescription,webappDisplay,webappOrientation,webappThemeColor,webappBackgroundColor',
        'addWindowstiles' => 'windowsTitle,windowsTooltip,windowsDns,addWindowsrss,windowsTileColor,windowsTooltipColor,windowsSize',
        'addWindowsrss'   => 'windowsRss,windowsRssFrequency',
        'addIos'          => 'iosTitle,iosStatusBarStyle,iosApp',
        'addAndroid'      => 'androidApp',
    ),

    // Fields
    'fields' => array
    (
        'name' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['name'],
            'exclude'              => true,
            'inputType'            => 'text',
            'eval'                 => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                  => "varchar(255) NOT NULL default ''"
        ),
        'alias' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['alias'],
            'exclude'              => true,
            'search'               => true,
            'flag'                 => 1,
            'inputType'            => 'text',
            'eval'                 => array('rgxp'=>'alias', 'unique'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
            'save_callback'        => array
            (
                array('tl_tiles', 'generateAlias')
            ),
            'sql'                  => "varbinary(128) NOT NULL default ''"
        ),
        'pages' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['pages'],
            'exclude'              => true,
            'search'               => true,
            'sorting'              => true,
            'flag'                 => 1,
            'inputType'            => 'pageTree',
            'eval'                 => array('multiple'=>true, 'fieldType'=>'checkbox', 'mandatory'=>true),
            'sql'                  => "blob NULL"
        ),
        'target' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['target'],
            'exclude'              => true,
            'search'               => true,
            'sorting'              => true,
            'flag'                 => 1,
            'inputType'            => 'fileTree',
            'eval'                 => array('multiple'=>false, 'fieldType'=>'radio', 'mandatory'=>true),
            'sql'                  => "blob NULL"
        ),
        'singleSRC' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_content']['singleSRC'],
            'exclude'              => true,
            'inputType'            => 'fileTree',
            'eval'                 => array('mandatory'=>true, 'filesOnly'=>true, 'fieldType'=>'radio', 'extensions'=>'jpg,jpeg,png,gif'),
            'sql'                  => "binary(16) NULL"
        ),
        'forceUpdate' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['forceUpdate'],
            'exclude'              => true,
            'search'               => true,
            'sorting'              => true,
            'flag'                 => 1,
            'inputType'            => 'checkbox',
            'eval'                 => array('doNotCopy'=>true, 'tl_class'=>'w50 m12'),
            'sql'                  => "char(1) NOT NULL default ''"
        ),
        'crop' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['crop'],
            'exclude'              => true,
            'inputType'            => 'select',
            'options'              => $GLOBALS['TL_CROP'],
            'reference'            => &$GLOBALS['TL_LANG']['MSC'],
            'eval'                 => array('helpwizard'=>true, 'tl_class'=>'w50'),
            'sql'                  => "varchar(64) NOT NULL default ''"
        ),


        'addWebapp' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['addWebapp'],
            'exclude'              => true,
            'inputType'            => 'checkbox',
            'eval'                 => array('submitOnChange'=>true, 'tl_class'=>'clr'),
            'sql'                  => "char(1) NOT NULL default ''"
        ),
        'webappName' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['webappName'],
            'exclude'              => true,
            'inputType'            => 'text',
            'eval'                 => array('maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                  => "varchar(255) NOT NULL default ''"
        ),
        'webappShortName' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['webappShortName'],
            'exclude'              => true,
            'inputType'            => 'text',
            'eval'                 => array('maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                  => "varchar(255) NOT NULL default ''"
        ),
        'webappDescription' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['webappDescription'],
            'exclude'              => true,
            'inputType'            => 'textarea',
            'eval'                 => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'clr'),
            'sql'                  => "mediumtext NULL"
        ),
        'webappDisplay' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['webappDisplay'],
            'exclude'              => true,
            'inputType'            => 'select',
            'options_callback'     => array('tl_tiles', 'getWebAppManifestDisplayOptions'),
            'reference'            => &$GLOBALS['TL_LANG']['tl_tiles']['options']['webappDisplay'],
            'eval'                 => array('chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'clr w50'),
            'sql'                  => "varchar(64) NOT NULL default ''"
        ),
        'webappOrientation' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['webappOrientation'],
            'exclude'              => true,
            'inputType'            => 'select',
            'options_callback'     => array('tl_tiles', 'getWebAppManifestOrientationOptions'),
            'reference'            => &$GLOBALS['TL_LANG']['tl_tiles']['options']['webappOrientation'],
            'eval'                 => array('chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
            'sql'                  => "varchar(64) NOT NULL default ''"
        ),
        'webappThemeColor' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['webappThemeColor'],
            'exclude'              => true,
            'inputType'            => 'text',
            'eval'                 => array('maxlength'=>6, 'multiple'=>true, 'size'=>2, 'colorpicker'=>true, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'clr w50 wizard'),
            'sql'                  => "varchar(64) NOT NULL default ''"
        ),
        'webappBackgroundColor' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['webappBackgroundColor'],
            'exclude'              => true,
            'inputType'            => 'text',
            'eval'                 => array('maxlength'=>6, 'multiple'=>true, 'size'=>2, 'colorpicker'=>true, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                  => "varchar(64) NOT NULL default ''"
        ),


        'addWindowstiles' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['addWindowstiles'],
            'exclude'              => true,
            'inputType'            => 'checkbox',
            'eval'                 => array('submitOnChange'=>true, 'tl_class'=>'clr'),
            'sql'                  => "char(1) NOT NULL default ''"
        ),
        'windowsTitle' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['windowsTitle'],
            'exclude'              => true,
            'inputType'            => 'text',
            'eval'                 => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                  => "varchar(255) NOT NULL default ''"
        ),
        'windowsTooltip' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['windowsTooltip'],
            'exclude'              => true,
            'inputType'            => 'text',
            'eval'                 => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                  => "varchar(255) NOT NULL default ''"
        ),
        'windowsDns' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['windowsDns'],
            'exclude'              => true,
            'inputType'            => 'text',
            'search'               => true,
            'eval'                 => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'clr'),
            'sql'                  => "varchar(255) NOT NULL default ''"
        ),
        'addWindowsrss' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['addWindowsrss'],
            'exclude'              => true,
            'inputType'            => 'checkbox',
            'eval'                 => array('submitOnChange'=>true, 'tl_class'=>'clr'),
            'sql'                  => "char(1) NOT NULL default ''"
        ),
        'windowsRss' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['windowsRss'],
            'exclude'              => true,
            'inputType'            => 'text',
            'eval'                 => array('rgxp'=>'url', 'decodeEntities'=>true, 'tl_class'=>'w50'),
            'sql'                  => "varchar(255) NOT NULL default ''"
        ),
        'windowsRssFrequency' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['windowsRssFrequency'],
            'exclude'              => true,
            'inputType'            => 'text',
            'eval'                 => array('rgxp'=>'digit', 'decodeEntities'=>true, 'tl_class'=>'w50'),
            'sql'                  => "int(10) NOT NULL default '30'"
        ),
        'windowsTileColor' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['windowsTileColor'],
            'inputType'            => 'text',
            'eval'                 => array('maxlength'=>6, 'multiple'=>true, 'size'=>2, 'colorpicker'=>true, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                  => "varchar(64) NOT NULL default ''"
        ),
        'windowsTooltipColor' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['windowsTooltipColor'],
            'inputType'            => 'text',
            'eval'                 => array('maxlength'=>6, 'multiple'=>true, 'size'=>2, 'colorpicker'=>true, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                  => "varchar(64) NOT NULL default ''"
        ),
        'windowsSize' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['windowsSize'],
            'exclude'              => true,
            'inputType'            => 'text',
            'eval'                 => array('multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                  => "varchar(64) NOT NULL default ''"
        ),


        'addIos' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['addIos'],
            'exclude'              => true,
            'inputType'            => 'checkbox',
            'eval'                 => array('submitOnChange'=>true, 'tl_class'=>'clr'),
            'sql'                  => "char(1) NOT NULL default ''"
        ),
        'iosTitle' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['iosTitle'],
            'exclude'              => true,
            'inputType'            => 'text',
            'eval'                 => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                  => "varchar(255) NOT NULL default ''"
        ),
        'iosApp' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['iosApp'],
            'exclude'              => true,
            'search'               => true,
            'sorting'              => true,
            'flag'                 => 1,
            'inputType'            => 'checkbox',
            'eval'                 => array('doNotCopy'=>true, 'tl_class'=>'w50 m12'),
            'sql'                  => "char(1) NOT NULL default ''"
        ),
        'iosStatusBarStyle' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['iosStatusBarStyle'],
            'exclude'              => true,
            'search'               => true,
            'sorting'              => true,
            'flag'                 => 1,
            'inputType'            => 'select',
            'options_callback'     => array('tl_tiles', 'getIosStatusBarStyleOptions'),
            'reference'            => &$GLOBALS['TL_LANG']['tl_tiles']['options']['iosStatusBarStyle'],
            'eval'                 => array('tl_class'=>'w50'),
            'sql'                  => "varchar(32) NOT NULL default ''"
        ),


        'addAndroid' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['addAndroid'],
            'exclude'              => true,
            'inputType'            => 'checkbox',
            'eval'                 => array('submitOnChange'=>true, 'tl_class'=>'clr'),
            'sql'                  => "char(1) NOT NULL default ''"
        ),
        'androidApp' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['androidApp'],
            'exclude'              => true,
            'search'               => true,
            'sorting'              => true,
            'flag'                 => 1,
            'inputType'            => 'checkbox',
            'eval'                 => array('doNotCopy'=>true, 'tl_class'=>'w50 m12'),
            'sql'                  => "char(1) NOT NULL default ''"
        ),


        'preview' => array
        (
            'input_field_callback' => array('tl_tiles', 'previewIcons'),
            'eval'                 => array('doNotShow'=>true)
        ),
        'published' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['published'],
            'exclude'              => true,
            'search'               => true,
            'sorting'              => true,
            'flag'                 => 1,
            'inputType'            => 'checkbox',
            'eval'                 => array('doNotCopy'=>true),
            'sql'                  => "char(1) NOT NULL default ''"
        ),
        'start' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['start'],
            'exclude'              => true,
            'inputType'            => 'text',
            'eval'                 => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                  => "varchar(10) NOT NULL default ''"
        ),
        'stop' => array
        (
            'label'                => &$GLOBALS['TL_LANG']['tl_tiles']['stop'],
            'exclude'              => true,
            'inputType'            => 'text',
            'eval'                 => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                  => "varchar(10) NOT NULL default ''"
        ),
        'id' => array
        (
            'sql'                  => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array
        (
            'sql'                  => "int(10) unsigned NOT NULL default '0'",
        ),
        'tstamp' => array
        (
            'sql'                  => "int(10) unsigned NOT NULL default '0'"
        ),
        'sorting' => array
        (
            'sql'                  => "int(10) unsigned NOT NULL default '0'",
        ),
    )
);


/**
 * Class tl_tiles
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
        if (!$dc->id)
        {
            return;
        }
        
        \Trilobit\TilesBundle\Tiles::generateTiles($dc->activeRecord);
    }


    /**
     * @param DataContainer $dc
     * @return string|void
     */
    public function previewIcons(\DataContainer $dc)
    {
        // Return if there is no ID
        if (!$dc->id)
        {
            return;
        }
    
        return '<div class="tl_box"><div id="tiles_images">'
            . \Trilobit\TilesBundle\Tiles::previewIcons($dc->activeRecord)
            . '</div></div>'
            ;
    }


    /**
     * @param $varValue
     * @param DataContainer $dc
     * @return mixed|string
     * @throws Exception
     */
    public function generateAlias($varValue, DataContainer $dc)
    {
        $autoAlias = false;

        // Generate alias if there is none
        if ($varValue == '')
        {
            $autoAlias = true;
            $varValue = standardize(StringUtil::restoreBasicEntities($dc->activeRecord->name));
        }

        $objAlias = $this->Database->prepare("SELECT id FROM tl_tiles WHERE alias=?")
            ->execute($varValue);

        // Check whether the news alias exists
        if ($objAlias->numRows > 1 && !$autoAlias)
        {
            throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
        }

        // Add ID to alias
        if ($objAlias->numRows && $autoAlias)
        {
            $varValue .= '-' . $dc->id;
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
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (strlen(Input::get('tid')))
        {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1));
            $this->redirect($this->getReferer());
        }

        $href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

        if (!$row['published'])
        {
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
        if (is_array($GLOBALS['TL_DCA']['tl_tiles']['fields']['published']['save_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_tiles']['fields']['published']['save_callback'] as $callback)
            {
                $this->import($callback[0]);
                $blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
            }
        }

        // Update the database
        $this->Database->prepare("UPDATE tl_tiles SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
            ->execute($intId);

        $objVersions->create();
    
        $strText = 'A new version of record "tl_tiles.id='.$intId.'" has been created ' . $this->getParentEntries('tl_tiles', $intId);
        $strFunction = 'tl_tiles toggleVisibility()';
        $strCategory = TL_GENERAL;
        
        $level = TL_ERROR === $strCategory ? LogLevel::ERROR : LogLevel::INFO;
        $logger = static::getContainer()->get('monolog.logger.contao');
    
        $logger->log($level, $strText, array('contao' => new ContaoContext($strFunction, $strCategory)));
    }


    /**
     * @param DataContainer $dc
     * @return array
     */
    public function getWebAppManifestDisplayOptions(DataContainer $dc)
    {
        return array_keys(\Trilobit\TilesBundle\Helper::getConfigData()['options']['webAppManifest']['display']);
    }


    /**
     * @param DataContainer $dc
     * @return array
     */
    public function getWebAppManifestOrientationOptions(DataContainer $dc)
    {
        return array_keys(\Trilobit\TilesBundle\Helper::getConfigData()['options']['webAppManifest']['orientation']);
    }


    /**
     * @param DataContainer $dc
     * @return array
     */
    public function getIosStatusBarStyleOptions(DataContainer $dc)
    {
        return array_keys(\Trilobit\TilesBundle\Helper::getConfigData()['options']['ios']['statusBar']);
    }
}
