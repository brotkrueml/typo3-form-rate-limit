<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Domain\Finishers;

use Symfony\Component\RateLimiter\LimiterInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use TYPO3\CMS\Core\RateLimiter\Storage\CachingFrameworkStorage;
use TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher;

final class RateLimitFinisher extends AbstractFinisher
{
    private CachingFrameworkStorage $storage;

    public function __construct(CachingFrameworkStorage $storage)
    {
        $this->storage = $storage;
    }

    protected function executeInternal(): ?string
    {
        $rateLimiter = $this->getRateLimiter();
        if (! $rateLimiter->consume()->isAccepted()) {
            $this->finisherContext->cancel();

            return 'Rate limit exceeded!';
        }

        return null;
    }

    private function getRateLimiter(): LimiterInterface
    {
        $policy = $this->parseOption('policy');
        $interval = $this->parseOption('interval');
        $limit = $this->parseOption('limit');

        // @phpstan-ignore-next-line Array with keys is not allowed. Use value object to pass data instead
        $config = [
            'id' => 'form-rate-limit',
            'policy' => $policy,
            'interval' => $interval,
            'limit' => $limit,
        ];

        $limiterFactory = new RateLimiterFactory($config, $this->storage);

        return $limiterFactory->create($this->buildKey());
    }

    private function buildKey(): string
    {
        $keyParts = [];

        $restrictions = $this->parseOption('restrictions');
        if (! \is_array($restrictions)) {
            return '';
        }

        foreach ($restrictions as $restriction) {
            if ($restriction === '__ipAddress') {
                // @phpstan-ignore-next-line Call to method getAttribute() on an unknown class TYPO3\CMS\Extbase\Mvc\Request.
                $keyParts[] = $this->finisherContext->getRequest()->getAttribute('normalizedParams')->getRemoteAddress();
                continue;
            }

            if ($restriction === '__formIdentifier') {
                $keyParts[] = $this->finisherContext->getFormRuntime()->getIdentifier();
                continue;
            }

            $keyParts[] = $restriction;
        }

        return \implode('-', $keyParts);
    }
}
