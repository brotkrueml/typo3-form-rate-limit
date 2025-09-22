<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Tests\Unit\RateLimiter;

use Brotkrueml\FormRateLimit\Domain\Dto\Options;
use Brotkrueml\FormRateLimit\RateLimiter\FormRateLimitFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\RateLimiter\LimiterStateInterface;
use Symfony\Component\RateLimiter\Policy\SlidingWindowLimiter;
use Symfony\Component\RateLimiter\Storage\StorageInterface;

#[CoversClass(FormRateLimitFactory::class)]
final class FormRateLimitFactoryTest extends TestCase
{
    #[Test]
    public function createRateLimiterWithSlidingWindowPolicyReturnsSlidingWindowLimiter(): void
    {
        $storageDummy = new class implements StorageInterface {
            public function save(LimiterStateInterface $limiterState): void {}

            public function fetch(string $limiterStateId): ?LimiterStateInterface
            {
                return null;
            }

            public function delete(string $limiterStateId): void {}
        };

        $subject = new FormRateLimitFactory($storageDummy);

        $options = new Options('1 minute', 1, 'sliding_window', ['__identifier']);
        $actual = $subject->createRateLimiter($options, 'some-form', '127.0.0.1');

        self::assertInstanceOf(SlidingWindowLimiter::class, $actual);
    }
}
