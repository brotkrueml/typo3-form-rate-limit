<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\RateLimiter\Storage;

final class FileStorageException extends \RuntimeException
{
    public static function fromInvalidStoragePath(string $storagePath): self
    {
        return new self(
            \sprintf(
                'Storage path "%s" is not available!',
                $storagePath,
            ),
            1758533582,
        );
    }
}
