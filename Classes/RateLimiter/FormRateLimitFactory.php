<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\RateLimiter;

use Brotkrueml\FormRateLimit\Dto\Options;
use Symfony\Component\RateLimiter\LimiterInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\RateLimiter\Storage\StorageInterface;

/**
 * @internal
 */
final class FormRateLimitFactory
{
    private StorageInterface $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function createRateLimiter(Options $options, string $formIdentifier, string $ipAddress): LimiterInterface
    {
        // @phpstan-ignore-next-line Array with keys is not allowed. Use value object to pass data instead
        $config = [
            'id' => 'form-rate-limit',
            'interval' => $options->getInterval(),
            'limit' => $options->getLimit(),
            'policy' => $options->getPolicy(),
        ];

        $limiterFactory = new RateLimiterFactory($config, $this->storage);

        return $limiterFactory->create($this->buildKey($options->getRestrictions(), $formIdentifier, $ipAddress));
    }

    /**
     * @param string[] $restrictions
     */
    private function buildKey(array $restrictions, string $formIdentifier, string $ipAddress): string
    {
        $keyParts = [];
        foreach ($restrictions as $restriction) {
            if ($restriction === '__ipAddress') {
                $keyParts[] = $ipAddress;
                continue;
            }

            if ($restriction === '__formIdentifier') {
                $keyParts[] = $formIdentifier;
                continue;
            }

            $keyParts[] = $restriction;
        }

        return \implode('-', $keyParts);
    }
}
