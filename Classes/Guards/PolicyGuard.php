<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Guards;

/**
 * @internal
 */
final class PolicyGuard
{
    private const ALLOWED_POLICIES = ['fixed_window', 'sliding_window'];

    /**
     * @param string|int|array{}|null $policy
     */
    public function guard($policy): string
    {
        if ($policy === null) {
            throw new \InvalidArgumentException('Policy must be set!', 1671448320);
        }

        if (! \is_string($policy)) {
            throw new \InvalidArgumentException('Policy must be a string!', 1671448321);
        }

        if (! \in_array($policy, self::ALLOWED_POLICIES, true)) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Policy must be one of the following: %s, "%s" given',
                    \implode(',', self::ALLOWED_POLICIES),
                    $policy
                ),
                1671448322
            );
        }

        return $policy;
    }
}
