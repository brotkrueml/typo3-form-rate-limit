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
final class IntervalGuard
{
    /**
     * @param string|int|array{}|null $interval
     */
    public function guard($interval): string
    {
        if ($interval === null) {
            throw new \InvalidArgumentException('Interval must be set!', 1671448702);
        }

        if (! \is_string($interval)) {
            throw new \InvalidArgumentException('Interval must be a string!', 1671448703);
        }

        if (@\DateInterval::createFromDateString($interval) === false) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Interval is not valid, "%s" given!',
                    $interval
                ),
                1671448704
            );
        }

        return $interval;
    }
}
