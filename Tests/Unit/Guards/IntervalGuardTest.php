<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Tests\Unit\Guards\IntervalGuard;

use Brotkrueml\FormRateLimit\Guards\IntervalGuard;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class IntervalGuardTest extends TestCase
{
    private IntervalGuard $subject;

    protected function setUp(): void
    {
        $this->subject = new IntervalGuard();
    }

    #[Test]
    #[DataProvider('providerForInvalidIntervals')]
    public function guardThrowsExceptionOnInvalidInterval(int|array|null $interval, string $expectedMessage, int $expectedCode): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);
        $this->expectExceptionCode($expectedCode);

        $this->subject->guard($interval);
    }

    public static function providerForInvalidIntervals(): iterable
    {
        yield 'interval is null' => [
            'interval' => null,
            'expectedMessage' => 'Interval must be set!',
            'expectedCode' => 1671448702,
        ];

        yield 'interval is an array' => [
            'interval' => [],
            'expectedMessage' => 'Interval must be a string!',
            'expectedCode' => 1671448703,
        ];

        yield 'interval is an int' => [
            'interval' => 42,
            'expectedMessage' => 'Interval must be a string!',
            'expectedCode' => 1671448703,
        ];
    }

    /**
     * @todo Remove when compatibility of PHP >= 8.3
     * @see https://www.php.net/manual/de/class.datemalformedintervalstringexception.php
     */
    #[Test]
    public function guardThrowsExceptionOnInvalidIntervalWhenCreating(): void
    {
        if (\version_compare(\PHP_VERSION, '8.3.0', '>=')) {
            self::markTestSkipped();
        }

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Interval is not valid, "This is invalid" given!');
        $this->expectExceptionCode(1671448704);

        $this->subject->guard('This is invalid');
    }

    #[Test]
    public function guardReturnsGivenIntervalWhenItIsValid(): void
    {
        $actual = $this->subject->guard('2 hours');

        self::assertSame('2 hours', $actual);
    }
}
