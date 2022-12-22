<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Tests\Unit\Domain\Dto;

use Brotkrueml\FormRateLimit\Domain\Dto\Options;
use PHPUnit\Framework\TestCase;

final class OptionsTest extends TestCase
{
    /**
     * @test
     */
    public function getterReturnCorrectValues(): void
    {
        $subject = new Options(
            '2 hours',
            42,
            'sliding_window',
            ['__formIdentifier', '{email}']
        );

        self::assertSame('2 hours', $subject->getInterval());
        self::assertSame(42, $subject->getLimit());
        self::assertSame('sliding_window', $subject->getPolicy());
        self::assertSame(['__formIdentifier', '{email}'], $subject->getRestrictions());
    }
}
