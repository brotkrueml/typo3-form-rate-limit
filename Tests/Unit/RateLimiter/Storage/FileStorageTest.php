<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Tests\Unit\RateLimiter\Storage;

use Brotkrueml\FormRateLimit\RateLimiter\Storage\FileStorage;
use Brotkrueml\FormRateLimit\Tests\Fixture\TestLimiter;
use FilesystemIterator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\RateLimiter\Policy\SlidingWindow;
use Symfony\Component\RateLimiter\Policy\Window;

final class FileStorageTest extends TestCase
{
    private const STORAGE_PATH = '/tmp/form_rate_limit_test';

    private FileStorage $subject;

    protected function setUp(): void
    {
        $this->subject = new FileStorage(self::STORAGE_PATH);
    }

    protected function tearDown(): void
    {
        if (! \is_dir(self::STORAGE_PATH)) {
            return;
        }

        \array_map('unlink', \glob(self::STORAGE_PATH . '/*'));
        \rmdir(self::STORAGE_PATH);
    }

    #[Test]
    public function saveAndThenFetchWorkWithWindowCorrectly(): void
    {
        $state = new Window('some-id', 60, 1);
        $this->subject->save($state);

        $actual = $this->subject->fetch('some-id');

        self::assertInstanceOf(Window::class, $actual);
        self::assertSame('some-id', $actual->getId());
    }

    #[Test]
    public function saveAndThenFetchWorkWithFixedWindowCorrectly(): void
    {
        $state = new SlidingWindow('some-id', 60);
        $this->subject->save($state);

        $actual = $this->subject->fetch('some-id');

        self::assertInstanceOf(SlidingWindow::class, $actual);
        self::assertSame('some-id', $actual->getId());
    }

    #[Test]
    public function fetchReturnsNullWhenIdNotAvailable(): void
    {
        $actual = $this->subject->fetch('non-existing-id');

        self::assertNull($actual);
    }

    #[Test]
    public function fetchReturnNullWhenClassIsNotAllowedByUnserialize(): void
    {
        $state = new TestLimiter();
        $this->subject->save($state);

        $actual = $this->subject->fetch('some-id');

        self::assertNull($actual);
    }

    #[Test]
    public function deleteReallyDeletesTheFile(): void
    {
        $state = new TestLimiter();
        $this->subject->save($state);

        $it = new FilesystemIterator(self::STORAGE_PATH);
        self::assertCount(1, $it);

        $this->subject->delete('some-id');

        self::assertCount(0, $it);
    }

    #[Test]
    public function deleteWithUnknownIdDoesNothing(): void
    {
        $state = new TestLimiter();
        $this->subject->save($state);

        $it = new FilesystemIterator(self::STORAGE_PATH);
        self::assertCount(1, $it);

        $this->subject->delete('another-id');

        self::assertCount(1, $it);
    }
}
