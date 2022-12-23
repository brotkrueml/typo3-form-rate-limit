<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Tests\Unit\Domain\Dto;

use Brotkrueml\FormRateLimit\Domain\Dto\CleanerCount;
use PHPUnit\Framework\TestCase;

final class CleanerCountTest extends TestCase
{
    /**
     * @test
     */
    public function propertiesAreSetCorrectly(): void
    {
        $subject = new CleanerCount(3, 2, 1);

        self::assertSame(3, $subject->getTotal());
        self::assertSame(2, $subject->getDeleted());
        self::assertSame(1, $subject->getErroneous());
    }
}
