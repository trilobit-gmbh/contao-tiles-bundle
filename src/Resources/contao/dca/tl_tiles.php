<?php

declare(strict_types=1);

/*
 * @copyright  trilobit GmbH
 * @author     trilobit GmbH <https://github.com/trilobit-gmbh>
 * @license    LGPL-3.0-or-later
 */

use Contao\DC_Table;
use Trilobit\TilesBundle\Helper;

/*
 * Table tl_tiles
 */
$GLOBALS['TL_DCA']['tl_tiles'] = [
    // Config
    'config' => [
        'dataContainer' => DC_Table::class,
        'enableVersioning' => true,
        'onsubmit_callback' => [
            [Helper::class, 'generateTiles'],
        ],
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],

    // List
    'list' => [
        'sorting' => [
            'mode' => 2,
            'flag' => 1,
            'rootPaste' => true,
            'icon' => 'modules.svg',
            'fields' => ['name'],
            'panelLayout' => 'filter;search,limit',
            'headerFields' => ['name'],
        ],
        'label' => [
            'fields' => ['name'],
        ],
        'global_operations' => [
            'all' => [
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
        ],
        'operations' => [
            'edit' => [
                'href' => 'act=edit',
                'icon' => 'edit.svg',
            ],
            'copy' => [
                'href' => 'act=copy',
                'icon' => 'copy.svg',
            ],
            'cut' => [
                'href' => 'act=paste&amp;mode=cut',
                'icon' => 'cut.svg',
            ],
            'delete' => [
                'href' => 'act=delete',
                'icon' => 'delete.svg',
                'attributes' => 'onclick="if (!confirm(\''.($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null).'\')) return false; Backend.getScrollOffset();"',
            ],
            'toggle' => [
                'attributes' => 'onclick="Backend.getScrollOffset();"',
                'haste_ajax_operation' => [
                    'field' => 'published',
                    'options' => [
                        [
                            'value' => '',
                            'icon' => 'invisible.svg',
                        ],
                        [
                            'value' => '1',
                            'icon' => 'visible.svg',
                        ],
                    ],
                ],
            ],
            'show' => [
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
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'alias' => [
            'exclude' => true,
            'search' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'alias', 'unique' => true, 'maxlength' => 128, 'tl_class' => 'w50'],
            'save_callback' => [
                [Helper::class, 'generateAlias'],
            ],
            'sql' => "varbinary(128) NOT NULL default ''",
        ],
        'pages' => [
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'pageTree',
            'eval' => ['multiple' => true, 'fieldType' => 'checkbox', 'mandatory' => true],
            'sql' => 'blob NULL',
        ],
        'target' => [
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'fileTree',
            'eval' => ['multiple' => false, 'fieldType' => 'radio', 'mandatory' => true],
            'sql' => 'blob NULL',
        ],
        'singleSRC' => [
            'exclude' => true,
            'inputType' => 'fileTree',
            'eval' => ['mandatory' => true, 'filesOnly' => true, 'fieldType' => 'radio', 'extensions' => 'jpg,jpeg,png,gif'],
            'sql' => 'binary(16) NULL',
        ],
        'forceUpdate' => [
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'checkbox',
            'eval' => ['doNotCopy' => true, 'tl_class' => 'w50 m12'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'crop' => [
            'exclude' => true,
            'inputType' => 'select',
            'options' => &$GLOBALS['TL_CROP'],
            'reference' => &$GLOBALS['TL_LANG']['MSC'],
            'eval' => ['helpwizard' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'addWebapp' => [
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['submitOnChange' => true, 'tl_class' => 'clr'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'webappName' => [
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'webappShortName' => [
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
            'sql' => 'mediumtext NULL',
        ],
        'webappDisplay' => [
            'exclude' => true,
            'inputType' => 'select',
            'options_callback' => [Helper::class, 'getWebAppManifestDisplayOptions'],
            'reference' => &$GLOBALS['TL_LANG']['tl_tiles']['options']['webappDisplay'],
            'eval' => ['chosen' => true, 'includeBlankOption' => true, 'tl_class' => 'clr w50'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'webappOrientation' => [
            'exclude' => true,
            'inputType' => 'select',
            'options_callback' => [Helper::class, 'getWebAppManifestOrientationOptions'],
            'reference' => &$GLOBALS['TL_LANG']['tl_tiles']['options']['webappOrientation'],
            'eval' => ['chosen' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'webappThemeColor' => [
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 6, 'multiple' => true, 'size' => 2, 'colorpicker' => true, 'isHexColor' => true, 'decodeEntities' => true, 'tl_class' => 'clr w50 wizard'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'webappBackgroundColor' => [
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 6, 'multiple' => true, 'size' => 2, 'colorpicker' => true, 'isHexColor' => true, 'decodeEntities' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'addWindowstiles' => [
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['submitOnChange' => true, 'tl_class' => 'clr'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'windowsTitle' => [
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
            'exclude' => true,
            'inputType' => 'text',
            'search' => true,
            'eval' => ['rgxp' => 'url', 'decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'clr'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'addWindowsrss' => [
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['submitOnChange' => true, 'tl_class' => 'clr'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'windowsRss' => [
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'url', 'decodeEntities' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'windowsRssFrequency' => [
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'digit', 'decodeEntities' => true, 'tl_class' => 'w50'],
            'sql' => "int(10) NOT NULL default '30'",
        ],
        'windowsTileColor' => [
            'inputType' => 'text',
            'eval' => ['maxlength' => 6, 'multiple' => true, 'size' => 2, 'colorpicker' => true, 'isHexColor' => true, 'decodeEntities' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'windowsTooltipColor' => [
            'inputType' => 'text',
            'eval' => ['maxlength' => 6, 'multiple' => true, 'size' => 2, 'colorpicker' => true, 'isHexColor' => true, 'decodeEntities' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'windowsSize' => [
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['multiple' => true, 'size' => 2, 'rgxp' => 'digit', 'nospace' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'addIos' => [
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['submitOnChange' => true, 'tl_class' => 'clr'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'iosTitle' => [
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'iosApp' => [
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'checkbox',
            'eval' => ['doNotCopy' => true, 'tl_class' => 'w50 m12'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'iosStatusBarStyle' => [
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'select',
            'options_callback' => [Helper::class, 'getIosStatusBarStyleOptions'],
            'reference' => &$GLOBALS['TL_LANG']['tl_tiles']['options']['iosStatusBarStyle'],
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(32) NOT NULL default ''",
        ],
        'addAndroid' => [
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['submitOnChange' => true, 'tl_class' => 'clr'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'androidApp' => [
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'checkbox',
            'eval' => ['doNotCopy' => true, 'tl_class' => 'w50 m12'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'preview' => [
            'input_field_callback' => [Helper::class, 'previewIcons'],
            'eval' => ['doNotShow' => true],
        ],
        'published' => [
            'toggle' => true,
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'sql' => "char(1) NOT NULL default '1'",
        ],
        'start' => [
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
        'stop' => [
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
        'id' => [
            'search' => true,
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp' => [
            'flag' => 6,
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'sorting' => [
            'sorting' => true,
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
    ],
];

/*
if (method_exists(DC_Table::class, 'toggle')) {
    $GLOBALS['TL_DCA']['tl_constants']['list']['operations']['toggle'] = [
        'href' => 'act=toggle&amp;field=published',
        'icon' => 'visible.svg',
        'showInHeader' => true,
    ];
}
*/
