<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\EventListener;

use Brotkrueml\FormRateLimit\Extension;
use TYPO3\CMS\Install\Service\Event\ModifyLanguagePacksEvent;

final class PreventLanguagePackDownload
{
    public function __invoke(ModifyLanguagePacksEvent $event): void
    {
        $event->removeExtension(Extension::KEY);
    }
}
