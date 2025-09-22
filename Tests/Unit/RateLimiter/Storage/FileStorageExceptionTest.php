<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Tests\Unit\RateLimiter\Storage;

use Brotkrueml\FormRateLimit\RateLimiter\Storage\FileStorageException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(FileStorageException::class)]
final class FileStorageExceptionTest extends TestCase
{
    #[Test]
    public function fromInvalidStoragePath(): void
    {
        $actual = FileStorageException::fromInvalidStoragePath('/some/path');

        self::assertSame('Storage path "/some/path" is not available!', $actual->getMessage());
        self::assertSame(1758533582, $actual->getCode());
    }
}
