<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Tests\Unit\Fixtures;

use Symfony\Component\RateLimiter\LimiterStateInterface;

final class TestLimiter implements LimiterStateInterface
{
    public function getId(): string
    {
        return 'some-id';
    }

    public function getExpirationTime(): ?int
    {
        return null;
    }
}
