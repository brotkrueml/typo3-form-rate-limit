<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\RateLimiter\Storage;

use Brotkrueml\FormRateLimit\Domain\Dto\CleanerCount;

/**
 * @internal
 */
class FileStorageCleaner
{
    private string $storagePath;
    private CleanerCount $count;

    public function __construct(string $storagePath)
    {
        $this->storagePath = $storagePath;
        $this->count = new CleanerCount();
    }

    public function cleanUp(): CleanerCount
    {
        if (! \is_dir($this->storagePath)) {
            return $this->count;
        }

        $it = new \FilesystemIterator($this->storagePath);
        foreach ($it as $file) {
            // @phpstan-ignore-next-line Parameter #1 $file of method Brotkrueml\FormRateLimit\RateLimiter\Storage\FileStorageCleaner::processFile() expects SplFileInfo, SplFileInfo|string given.
            $this->processFile($file);
        }

        return $this->count;
    }

    private function processFile(\SplFileInfo $file): void
    {
        if ($file->getType() !== 'file') {
            return;
        }
        if ($file->getExtension() !== '') {
            return;
        }

        $this->count->incrementTotal();

        $content = \file_get_contents($file->getPathname());

        if ($content === false) {
            $this->count->incrementErroneous();
            return;
        }

        $data = \unserialize($content);

        if ($data['expiry'] >= \time()) {
            return;
        }

        if (! \unlink($file->getPathname())) {
            $this->count->incrementErroneous();
        }

        $this->count->incrementDeleted();
    }
}
