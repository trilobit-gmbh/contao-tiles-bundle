<?php

declare(strict_types=1);

/*
 * @copyright  trilobit GmbH
 * @author     trilobit GmbH <https://github.com/trilobit-gmbh>
 * @license    LGPL-3.0-or-later
 */

$GLOBALS['TL_HOOKS']['generatePage'][] = ['Trilobit\TilesBundle\Tiles', 'generatePageHook'];

/*
 * Back end module
 */
$GLOBALS['BE_MOD']['trilobit']['tiles'] = [
    'tables' => ['tl_tiles'],
];

/*
 * Add css
 */
if (TL_MODE === 'BE') {
    $GLOBALS['TL_CSS'][] = 'bundles/trilobittiles/css/backend.css';
}
