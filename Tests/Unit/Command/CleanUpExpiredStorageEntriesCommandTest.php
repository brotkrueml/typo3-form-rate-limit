<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Tests\Unit\Command;

use Brotkrueml\FormRateLimit\Command\CleanUpExpiredStorageEntriesCommand;
use Brotkrueml\FormRateLimit\Domain\Dto\CleanerCount;
use Brotkrueml\FormRateLimit\RateLimiter\Storage\FileStorageCleaner;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

#[CoversClass(CleanUpExpiredStorageEntriesCommand::class)]
final class CleanUpExpiredStorageEntriesCommandTest extends TestCase
{
    /**
     * @var FileStorageCleaner&Stub
     */
    private Stub $fileStorageCleanerStub;
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $this->fileStorageCleanerStub = self::createStub(FileStorageCleaner::class);

        $command = new CleanUpExpiredStorageEntriesCommand($this->fileStorageCleanerStub);
        $this->commandTester = new CommandTester($command);
    }

    #[Test]
    public function executeReturnsCorrectResultsWithErroneousFiles(): void
    {
        $count = new CleanerCount();
        $count->incrementTotal();
        $count->incrementTotal();
        $count->incrementTotal();
        $count->incrementErroneous();
        $count->incrementDeleted();
        $count->incrementDeleted();

        $this->fileStorageCleanerStub
            ->method('cleanUp')
            ->willReturn($count);

        $this->commandTester->execute([]);

        self::assertStringContainsString('[WARNING] 1 files could not be deleted.', $this->commandTester->getDisplay());
        self::assertStringContainsString('[OK] 2 expired files were deleted successfully, 3 total files were available.', $this->commandTester->getDisplay());
        self::assertSame(Command::SUCCESS, $this->commandTester->getStatusCode());
    }

    #[Test]
    public function executeReturnsCorrectResultsWithoutErroneousFiles(): void
    {
        $count = new CleanerCount();
        $count->incrementTotal();
        $count->incrementTotal();
        $count->incrementTotal();
        $count->incrementDeleted();
        $count->incrementDeleted();

        $this->fileStorageCleanerStub
            ->method('cleanUp')
            ->willReturn($count);

        $this->commandTester->execute([]);

        self::assertStringNotContainsString('files could not be deleted.', $this->commandTester->getDisplay());
        self::assertStringContainsString('[OK] 2 expired files were deleted successfully, 3 total files were available.', $this->commandTester->getDisplay());
        self::assertSame(Command::SUCCESS, $this->commandTester->getStatusCode());
    }

    #[Test]
    public function exceptionIsCatchedCorrectly(): void
    {
        $this->fileStorageCleanerStub
            ->method('cleanUp')
            ->willThrowException(new \Exception('Some error', 42));

        $this->commandTester->execute([]);

        self::assertStringContainsString('[ERROR] An error occurred: Some error', $this->commandTester->getDisplay());
        self::assertSame(Command::FAILURE, $this->commandTester->getStatusCode());
    }
}
