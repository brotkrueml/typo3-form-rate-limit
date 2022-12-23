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

    public function __construct(string $storagePath)
    {
        $this->storagePath = $storagePath;
    }

    public function cleanUp(): CleanerCount
    {
        if (! \is_dir($this->storagePath)) {
            return new CleanerCount(0, 0, 0);
        }

        $it = new \FilesystemIterator($this->storagePath);
        $countTotal = 0;
        $countDeleted = 0;
        $countErroneous = 0;
        foreach ($it as $file) {
            /** @var \SplFileInfo $file */
            if ($file->getType() !== 'file') {
                continue;
            }
            if ($file->getExtension() !== '') {
                continue;
            }

            $countTotal++;

            $content = \file_get_contents($file->getPathname());
            if ($content === false) {
                $countErroneous++;
                continue;
            }
            try {
                /** @var array{state: string, expiry: int} $data */
                $data = \json_decode($content, true, 512, \JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                $countErroneous++;
                continue;
            }

            if ($data['expiry'] >= \time()) {
                continue;
            }

            if (! \unlink($file->getPathname())) {
                $countErroneous++;
            }

            $countDeleted++;
        }

        return new CleanerCount($countTotal, $countDeleted, $countErroneous);
    }
}
