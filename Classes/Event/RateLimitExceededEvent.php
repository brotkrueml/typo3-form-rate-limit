<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Event;

use Brotkrueml\FormRateLimit\Domain\Dto\Options;
use Psr\Http\Message\ServerRequestInterface;

final readonly class RateLimitExceededEvent
{
    public function __construct(
        private string $formIdentifier,
        private Options $options,
        private ServerRequestInterface $request,
    ) {}

    public function getFormIdentifier(): string
    {
        return $this->formIdentifier;
    }

    public function getInterval(): string
    {
        return $this->options->interval;
    }

    public function getLimit(): int
    {
        return $this->options->limit;
    }

    public function getPolicy(): string
    {
        return $this->options->policy;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
