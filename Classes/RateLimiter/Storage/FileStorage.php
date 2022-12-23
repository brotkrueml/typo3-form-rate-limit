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

        $content = [
            'state' => \serialize($limiterState),
            'expiry' => \time() + $limiterState->getExpirationTime() + 1,
        ];

        \file_put_contents(
            $this->getFilePath($limiterState->getId()),
            \json_encode($content, \JSON_THROW_ON_ERROR)
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

        try {
            /** @var array{state: string, expiry: int} $data */
            $data = \json_decode($content, true, 512, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return null;
        }

        $state = \unserialize($data['state'], [
            'allowed_classes' => [Window::class, SlidingWindow::class],
        ]);
        if ($state instanceof LimiterStateInterface) {
            return $state;
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
