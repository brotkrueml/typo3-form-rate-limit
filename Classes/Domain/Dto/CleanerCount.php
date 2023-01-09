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
final class CleanerCount
{
    private int $total = 0;
    private int $deleted = 0;
    private int $erroneous = 0;

    public function incrementTotal(): void
    {
        $this->total++;
    }

    public function incrementDeleted(): void
    {
        $this->deleted++;
    }

    public function incrementErroneous(): void
    {
        $this->erroneous++;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getDeleted(): int
    {
        return $this->deleted;
    }

    public function getErroneous(): int
    {
        return $this->erroneous;
    }
}
