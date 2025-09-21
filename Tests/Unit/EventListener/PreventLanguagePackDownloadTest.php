<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Tests\Unit\EventListener;

use Brotkrueml\FormRateLimit\EventListener\PreventLanguagePackDownload;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Install\Service\Event\ModifyLanguagePacksEvent;

final class PreventLanguagePackDownloadTest extends TestCase
{
    #[Test]
    public function extensionIsRemoved(): void
    {
        if ((new Typo3Version())->getMajorVersion() < 12) {
            self::markTestSkipped('Event class is available only for TYPO3 v12+');
        }

        $event = new ModifyLanguagePacksEvent([
            'form_rate_limit' => [],
        ]);
        $subject = new PreventLanguagePackDownload();
        $subject->__invoke($event);

        self::assertArrayNotHasKey('form_rate_limit', $event->getExtensions());
    }
}
