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
    private int $total;
    private int $deleted;
    private int $erroneous;

    public function __construct(int $total, int $deleted, int $erroneous)
    {
        $this->total = $total;
        $this->deleted = $deleted;
        $this->erroneous = $erroneous;
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
