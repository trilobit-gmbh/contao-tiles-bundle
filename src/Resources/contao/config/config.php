<?php

/**
 * Register hook
 */
$GLOBALS['TL_HOOKS']['generatePage'][] = array('Trilobit\TilesBundle\Tiles', 'generatePageHook');


/**
 * Back end module
 */
$GLOBALS['BE_MOD']['trilobit']['tiles'] = array
(
    'tables' => array('tl_tiles'),
);


/**
 * Add css
 */
if (TL_MODE == 'BE')
{
    $GLOBALS['TL_CSS'][] = 'bundles/trilobittiles/css/backend.css';
}
