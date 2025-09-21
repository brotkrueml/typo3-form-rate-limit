<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Domain\Dto;

/**
 * @internal
 */
final class Options
{
    /**
     * @param string[] $restrictions
     */
    public function __construct(
        private readonly string $interval,
        private readonly int $limit,
        private readonly string $policy,
        private readonly array $restrictions
    ) {
    }

    public function getInterval(): string
    {
        return $this->interval;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getPolicy(): string
    {
        return $this->policy;
    }

    /**
     * @return string[]
     */
    public function getRestrictions(): array
    {
        return $this->restrictions;
    }
}
