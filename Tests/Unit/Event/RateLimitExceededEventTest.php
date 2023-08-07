<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Tests\Unit\Event;

use Brotkrueml\FormRateLimit\Domain\Dto\Options;
use Brotkrueml\FormRateLimit\Event\RateLimitExceededEvent;
use PHPUnit\Framework\TestCase;

final class RateLimitExceededEventTest extends TestCase
{
    /**
     * @test
     */
    public function valuesFromGettersReturnedCorrectly(): void
    {
        $subject = new RateLimitExceededEvent(
            'mypage-42',
            new Options(
                '1 hour',
                3,
                'sliding_window',
                []
            )
        );

        self::assertSame('mypage-42', $subject->getFormIdentifier());
        self::assertSame('1 hour', $subject->getInterval());
        self::assertSame(3, $subject->getLimit());
        self::assertSame('sliding_window', $subject->getPolicy());
    }
}
