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
final readonly class RestrictionsGuard
{
    /**
     * @param string|int|array<int,string>|null $restrictions
     * @return list<string>
     */
    public function guard($restrictions): array
    {
        if ($restrictions === null) {
            throw new \InvalidArgumentException('Restrictions must be set!', 1671727527);
        }

        if (! \is_array($restrictions)) {
            throw new \InvalidArgumentException('Restrictions must be an array!', 1671727528);
        }

        if ($restrictions === []) {
            throw new \InvalidArgumentException('Restrictions must not be an empty array!', 1671727529);
        }

        foreach ($restrictions as $restriction) {
            if (! \is_string($restriction)) {
                throw new \InvalidArgumentException('A single restrictions must be a string!', 1671727530);
            }
        }

        return \array_values($restrictions);
    }
}
