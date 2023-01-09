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
    private CleanerCount $subject;

    protected function setUp(): void
    {
        $this->subject = new CleanerCount();
    }

    /**
     * @test
     */
    public function propertiesInitialisedCorrectly(): void
    {
        self::assertSame(0, $this->subject->getTotal());
        self::assertSame(0, $this->subject->getErroneous());
        self::assertSame(0, $this->subject->getDeleted());
    }

    /**
     * @test
     */
    public function incrementAndGetTotal(): void
    {
        $this->subject->incrementTotal();
        $this->subject->incrementTotal();

        self::assertSame(2, $this->subject->getTotal());
        self::assertSame(0, $this->subject->getErroneous());
        self::assertSame(0, $this->subject->getDeleted());
    }

    /**
     * @test
     */
    public function incrementAndGetErroneous(): void
    {
        $this->subject->incrementErroneous();
        $this->subject->incrementErroneous();

        self::assertSame(0, $this->subject->getTotal());
        self::assertSame(2, $this->subject->getErroneous());
        self::assertSame(0, $this->subject->getDeleted());
    }

    /**
     * @test
     */
    public function incrementAndGetDeleted(): void
    {
        $this->subject->incrementDeleted();
        $this->subject->incrementDeleted();

        self::assertSame(0, $this->subject->getTotal());
        self::assertSame(0, $this->subject->getErroneous());
        self::assertSame(2, $this->subject->getDeleted());
    }
}
