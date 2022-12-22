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
    private string $interval;
    private int $limit;
    private string $policy;
    /**
     * @var string[]
     */
    private array $restrictions;

    /**
     * @param string[] $restrictions
     */
    public function __construct(string $interval, int $limit, string $policy, array $restrictions)
    {
        $this->interval = $interval;
        $this->limit = $limit;
        $this->policy = $policy;
        $this->restrictions = $restrictions;
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
