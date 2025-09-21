<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Tests\Unit\Guards;

use Brotkrueml\FormRateLimit\Guards\RestrictionsGuard;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RestrictionsGuardTest extends TestCase
{
    private RestrictionsGuard $subject;

    protected function setUp(): void
    {
        $this->subject = new RestrictionsGuard();
    }

    #[Test]
    #[DataProvider('providerForInvalidRestrictions')]
    public function guardThrowsExceptionOnInvalidRestrictions(string|array|null $restrictions, string $expectedMessage, int $expectedCode): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);
        $this->expectExceptionCode($expectedCode);

        $this->subject->guard($restrictions);
    }

    public static function providerForInvalidRestrictions(): iterable
    {
        yield 'restrictions is null' => [
            'restrictions' => null,
            'expectedMessage' => 'Restrictions must be set!',
            'expectedCode' => 1671727527,
        ];

        yield 'restrictions is a string' => [
            'restrictions' => 'invalid restriction',
            'expectedMessage' => 'Restrictions must be an array!',
            'expectedCode' => 1671727528,
        ];

        yield 'restrictions is an empty array' => [
            'restrictions' => [],
            'expectedMessage' => 'Restrictions must not be an empty array!',
            'expectedCode' => 1671727529,
        ];

        yield 'restrictions is an array but one value is not a string' => [
            'restrictions' => [
                '__formIdentifier',
                42,
            ],
            'expectedMessage' => 'A single restrictions must be a string!',
            'expectedCode' => 1671727530,
        ];
    }

    #[Test]
    public function guardReturnsGivenRestrictionsWhenItIsValid(): void
    {
        $actual = $this->subject->guard([
            '__formIdentifier',
            'someKey' => '__ipAddress',
            '{email}',
        ]);

        self::assertSame([
            '__formIdentifier',
            '__ipAddress',
            '{email}',
        ], $actual);
    }
}
