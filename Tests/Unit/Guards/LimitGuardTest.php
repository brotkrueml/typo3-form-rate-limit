<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Tests\Unit\Guards\IntervalGuard;

use Brotkrueml\FormRateLimit\Guards\LimitGuard;
use PHPUnit\Framework\TestCase;

final class LimitGuardTest extends TestCase
{
    private LimitGuard $subject;

    protected function setUp(): void
    {
        $this->subject = new LimitGuard();
    }

    /**
     * @test
     * @dataProvider providerForInvalidLimits
     */
    public function guardThrowsExceptionOnInvalidLimit($limit, string $expectedMessage, int $expectedCode): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);
        $this->expectExceptionCode($expectedCode);

        $this->subject->guard($limit);
    }

    public function providerForInvalidLimits(): iterable
    {
        yield 'limit is null' => [
            'interval' => null,
            'expectedMessage' => 'Limit must be set!',
            'expectedCode' => 1671449026,
        ];

        yield 'limit is an array' => [
            'limit' => [],
            'expectedMessage' => 'Limit must be numeric!',
            'expectedCode' => 1671449027,
        ];

        yield 'limit is not numeric' => [
            'interval' => '42abc',
            'expectedMessage' => 'Limit must be numeric!',
            'expectedCode' => 1671449027,
        ];

        yield 'limit is a negative numeric string' => [
            'interval' => '-42',
            'expectedMessage' => 'Limit must be greater than 0!',
            'expectedCode' => 1671449028,
        ];

        yield 'limit is 0' => [
            'interval' => 0,
            'expectedMessage' => 'Limit must be greater than 0!',
            'expectedCode' => 1671449028,
        ];
    }

    /**
     * @test
     */
    public function guardReturnsGivenLimitWhenItIsAnInteger(): void
    {
        $actual = $this->subject->guard(42);

        self::assertSame(42, $actual);
    }

    /**
     * @test
     */
    public function guardReturnsGivenLimitWhenItIsANumericString(): void
    {
        $actual = $this->subject->guard('42');

        self::assertSame(42, $actual);
    }
}
