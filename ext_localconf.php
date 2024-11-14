<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

ExtensionManagementUtility::addTypoScriptSetup('
    module.tx_form {
        settings {
            yamlConfigurations {
                1671440351 = EXT:form_rate_limit/Configuration/Yaml/FormSetup.yaml
            }
        }
    }
');
