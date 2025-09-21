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
final readonly class Options
{
    /**
     * @param list<string> $restrictions
     */
    public function __construct(
        public string $interval,
        public int $limit,
        public string $policy,
        public array $restrictions,
    ) {}
}
