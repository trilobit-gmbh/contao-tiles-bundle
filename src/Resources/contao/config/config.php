<?php

declare(strict_types=1);

/*
 * @copyright  trilobit GmbH
 * @author     trilobit GmbH <https://github.com/trilobit-gmbh>
 * @license    LGPL-3.0-or-later
 */

use Trilobit\TilesBundle\Tiles;

$GLOBALS['TL_HOOKS']['generatePage'][] = [Tiles::class, 'generatePageHook'];

/*
 * Back end module
 */
$GLOBALS['BE_MOD']['trilobit']['tiles'] = [
    'tables' => ['tl_tiles'],
];

/*
 * Add css
 */
$request = \Contao\System::getContainer()
    ->get('request_stack')
    ->getCurrentRequest()
;
if ($request && \Contao\System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request)) {
    $GLOBALS['TL_CSS'][] = 'bundles/trilobittiles/css/backend.css';
}
