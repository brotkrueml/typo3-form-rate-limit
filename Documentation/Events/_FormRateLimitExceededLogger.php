<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace YourVendor\YourExtension\EventListener;

use Brotkrueml\FormRateLimit\Event\RateLimitExceededEvent;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Attribute\AsEventListener;

#[AsEventListener(
    identifier: 'your-extension/form-rate-limit-exceeded-logger',
)]
final readonly class FormRateLimitExceededLogger
{
    public function __construct(
        private LoggerInterface $logger,
    ) {}

    public function __invoke(RateLimitExceededEvent $event): void
    {
        $this->logger->warning(
            'The form with identifier "{formIdentifier}" was sent more than {limit} times within {interval}',
            [
                'formIdentifier' => $event->getFormIdentifier(),
                'limit' => $event->getLimit(),
                'interval' => $event->getInterval(),
            ],
        );
    }
}
