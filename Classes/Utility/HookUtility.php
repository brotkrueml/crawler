<?php

declare(strict_types=1);

namespace AOE\Crawler\Utility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 AOE GmbH <dev@aoe.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use AOE\Crawler\Hooks\ProcessCleanUpHook;

/**
 * Class HookUtility
 *
 * @codeCoverageIgnore
 */
class HookUtility
{
    /**
     * Registers hooks
     *
     * @param string $extKey
     */
    public static function registerHooks($extKey): void
    {
        // Activating Crawler cli_hooks
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$extKey]['cli_hooks'][] =
            ProcessCleanUpHook::class;

        // Activating refresh hooks
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$extKey]['refresh_hooks'][] =
            ProcessCleanUpHook::class;

        // Env-dependent
        if (TYPO3_MODE == 'BE') {
            self::registerBackendHooks($extKey);
        }
    }

    private static function registerBackendHooks(string $extKey): void
    {
        // DataHandler clear page cache pre-processing
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearPageCacheEval'][] =
            "AOE\Crawler\Hooks\DataHandlerHook->addFlushedPagesToCrawlerQueue";
    }
}
