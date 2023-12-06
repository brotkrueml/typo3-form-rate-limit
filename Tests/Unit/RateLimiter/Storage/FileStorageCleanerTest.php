<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Tests\Unit\RateLimiter\Storage;

use Brotkrueml\FormRateLimit\RateLimiter\Storage\FileStorageCleaner;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brotkrueml\FormRateLimit\RateLimiter\Storage\FileStorageCleaner
 */
final class FileStorageCleanerTest extends TestCase
{
    private const STORAGE_PATH = '/tmp/form_rate_limit_test';

    private FileStorageCleaner $subject;

    protected function setUp(): void
    {
        $this->subject = new FileStorageCleaner(self::STORAGE_PATH);
        $this->cleanUpFiles();
        \mkdir(self::STORAGE_PATH);
    }

    protected function tearDown(): void
    {
        $this->cleanUpFiles();
    }

    private function cleanUpFiles(): void
    {
        if (! \is_dir(self::STORAGE_PATH)) {
            return;
        }

        \array_map('unlink', \glob(self::STORAGE_PATH . '/*'));
        \rmdir(self::STORAGE_PATH);
    }

    /**
     * @test
     */
    public function noFilesAvailableReturnsCorrectCount(): void
    {
        $actual = $this->subject->cleanUp();

        self::assertSame(0, $actual->getTotal());
        self::assertSame(0, $actual->getDeleted());
        self::assertSame(0, $actual->getErroneous());
    }

    /**
     * @test
     */
    public function allFilesExpireInTheFuture(): void
    {
        $this->storeFileInStorage(\time() + 600);
        $this->storeFileInStorage(\time() + 800);
        $this->storeFileInStorage(\time() + 900);

        $actual = $this->subject->cleanUp();

        self::assertSame(3, $actual->getTotal());
        self::assertSame(0, $actual->getDeleted());
        self::assertSame(0, $actual->getErroneous());
    }

    /**
     * @test
     */
    public function withSomeFilesExpiryInThePast(): void
    {
        $this->storeFileInStorage(\time() - 10);
        $this->storeFileInStorage(\time() - 1);
        $this->storeFileInStorage(\time());
        $this->storeFileInStorage(\time() + 800);
        $this->storeFileInStorage(\time() + 900);

        $actual = $this->subject->cleanUp();

        self::assertSame(5, $actual->getTotal());
        self::assertSame(2, $actual->getDeleted());
        self::assertSame(0, $actual->getErroneous());
    }

    /**
     * @test
     */
    public function withSomeErroneousFiles(): void
    {
        \file_put_contents(tempnam(self::STORAGE_PATH, 'frl_'), 'some content');
        \file_put_contents(tempnam(self::STORAGE_PATH, 'frl_'), 'another content');
        $this->storeFileInStorage(\time() + 800);
        $this->storeFileInStorage(\time() + 900);

        $actual = $this->subject->cleanUp();

        self::assertSame(4, $actual->getTotal());
        self::assertSame(0, $actual->getDeleted());
        self::assertSame(2, $actual->getErroneous());
    }

    private function storeFileInStorage(int $expiry): void
    {
        $filePath = tempnam(self::STORAGE_PATH, 'frl_');
        $data = [
            'expiry' => $expiry,
        ];
        \file_put_contents($filePath, \serialize($data));
    }
}
