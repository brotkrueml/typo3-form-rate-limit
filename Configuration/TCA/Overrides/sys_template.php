<?php

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

defined('TYPO3') || die();

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    Brotkrueml\FormRateLimit\Extension::KEY,
    'Configuration/TypoScript',
    'Form Rate Limit'
);
