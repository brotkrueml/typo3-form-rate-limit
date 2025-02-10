<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Tests\Unit\Guards\IntervalGuard;

use Brotkrueml\FormRateLimit\Guards\PolicyGuard;
use PHPUnit\Framework\TestCase;

final class PolicyGuardTest extends TestCase
{
    private PolicyGuard $subject;

    protected function setUp(): void
    {
        $this->subject = new PolicyGuard();
    }

    /**
     * @test
     * @dataProvider providerForInvalidPolicies
     */
    public function guardThrowsExceptionOnInvalidPolicy($policy, string $expectedMessage, int $expectedCode): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);
        $this->expectExceptionCode($expectedCode);

        $this->subject->guard($policy);
    }

    public static function providerForInvalidPolicies(): iterable
    {
        yield 'policy is null' => [
            'policy' => null,
            'expectedMessage' => 'Policy must be set!',
            'expectedCode' => 1671448320,
        ];

        yield 'policy is an array' => [
            'policy' => [],
            'expectedMessage' => 'Policy must be a string!',
            'expectedCode' => 1671448321,
        ];

        yield 'policy is an int' => [
            'policy' => 42,
            'expectedMessage' => 'Policy must be a string!',
            'expectedCode' => 1671448321,
        ];

        yield 'policy is an invalid string' => [
            'policy' => 'This is invalid',
            'expectedMessage' => 'Policy must be one of the following: fixed_window,sliding_window, "This is invalid" given',
            'expectedCode' => 1671448322,
        ];
    }

    /**
     * @test
     */
    public function guardReturnsGivenFixedWindowPolicy(): void
    {
        $actual = $this->subject->guard('fixed_window');

        self::assertSame('fixed_window', $actual);
    }

    /**
     * @test
     */
    public function guardReturnsGivenSlidingWindowPolicy(): void
    {
        $actual = $this->subject->guard('sliding_window');

        self::assertSame('sliding_window', $actual);
    }
}
