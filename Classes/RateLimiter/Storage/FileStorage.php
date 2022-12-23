<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\RateLimiter\Storage;

use Symfony\Component\RateLimiter\LimiterStateInterface;
use Symfony\Component\RateLimiter\Policy\SlidingWindow;
use Symfony\Component\RateLimiter\Policy\Window;
use Symfony\Component\RateLimiter\Storage\StorageInterface;

/**
 * @internal
 */
final class FileStorage implements StorageInterface
{
    private string $storagePath;

    public function __construct(string $storagePath)
    {
        $this->storagePath = $storagePath;
    }

    public function save(LimiterStateInterface $limiterState): void
    {
        $this->ensureStoragePathExists();

        \file_put_contents(
            $this->getFilePath($limiterState->getId()),
            serialize($limiterState)
        );
    }

    public function fetch(string $limiterStateId): ?LimiterStateInterface
    {
        $filePath = $this->getFilePath($limiterStateId);
        if (! \is_file($filePath)) {
            return null;
        }

        $content = \file_get_contents($filePath);
        if ($content === false) {
            return null;
        }

        $value = \unserialize($content, [
            'allowed_classes' => [Window::class, SlidingWindow::class],
        ]);
        if ($value instanceof LimiterStateInterface) {
            return $value;
        }

        return null;
    }

    public function delete(string $limiterStateId): void
    {
        $filePath = $this->getFilePath($limiterStateId);
        if (\is_file($filePath)) {
            \unlink($filePath);
        }
    }

    private function ensureStoragePathExists(): void
    {
        if (! \is_dir($this->storagePath)) {
            \mkdir($this->storagePath, 0755, true);
        }
    }

    private function getFilePath(string $limiterStateId): string
    {
        return $this->storagePath . '/' . \hash('sha256', $limiterStateId);
    }
}
