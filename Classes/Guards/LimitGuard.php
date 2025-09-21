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
final class LimitGuard
{
    /**
     * @param string|int|array{}|null $limit
     */
    public function guard($limit): int
    {
        if ($limit === null) {
            throw new \InvalidArgumentException('Limit must be set!', 1671449026);
        }

        if (! \is_numeric($limit)) {
            throw new \InvalidArgumentException('Limit must be numeric!', 1671449027);
        }

        $limit = (int) $limit;
        if ($limit < 1) {
            throw new \InvalidArgumentException('Limit must be greater than 0!', 1671449028);
        }

        return $limit;
    }
}
