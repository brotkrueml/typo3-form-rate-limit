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
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Install\Service\Event\ModifyLanguagePacksEvent;

#[AsEventListener(
    identifier: 'form-rate-limit/prevent-language-pack-download',
)]
final readonly class PreventLanguagePackDownload
{
    public function __invoke(ModifyLanguagePacksEvent $event): void
    {
        $event->removeExtension(Extension::KEY);
    }
}
