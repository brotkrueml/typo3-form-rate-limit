<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Domain\Finishers;

use Brotkrueml\FormRateLimit\Guards\IntervalGuard;
use Brotkrueml\FormRateLimit\Guards\LimitGuard;
use Brotkrueml\FormRateLimit\Guards\PolicyGuard;
use Symfony\Component\RateLimiter\LimiterInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use TYPO3\CMS\Core\RateLimiter\Storage\CachingFrameworkStorage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher;

final class RateLimitFinisher extends AbstractFinisher
{
    private CachingFrameworkStorage $storage;
    private IntervalGuard $intervalGuard;
    private LimitGuard $limitGuard;
    private PolicyGuard $policyGuard;
    private string $interval;
    private int $limit;
    private string $policy;

    /**
     * @var array<string, string|int|list<string>>
     * @phpstan-ignore-next-line Array with keys is not allowed. Use value object to pass data instead
     */
    protected $defaultOptions = [
        'policy' => 'sliding_window',
        'interval' => '1 hour',
        'limit' => 1,
        'restrictions' => [
            '__ipAddress',
            '__formIdentifier',
        ],
        'template' => 'EXT:form_rate_limit/Resources/Private/Templates/RateLimitExceeded.html',
    ];

    public function __construct(CachingFrameworkStorage $storage)
    {
        $this->storage = $storage;
        $this->intervalGuard = new IntervalGuard();
        $this->limitGuard = new LimitGuard();
        $this->policyGuard = new PolicyGuard();
    }

    protected function executeInternal(): ?string
    {
        $this->interval = $this->intervalGuard->guard($this->parseOption('interval'));
        $this->limit = $this->limitGuard->guard($this->parseOption('limit'));
        $this->policy = $this->policyGuard->guard($this->parseOption('policy'));

        $rateLimiter = $this->getRateLimiter();
        if (! $rateLimiter->consume()->isAccepted()) {
            $this->finisherContext->cancel();

            return $this->renderExceededMessage();
        }

        return null;
    }

    private function getRateLimiter(): LimiterInterface
    {
        // @phpstan-ignore-next-line Array with keys is not allowed. Use value object to pass data instead
        $config = [
            'id' => 'form-rate-limit',
            'interval' => $this->interval,
            'limit' => $this->limit,
            'policy' => $this->policy,
        ];

        $limiterFactory = new RateLimiterFactory($config, $this->storage);

        return $limiterFactory->create($this->buildKey());
    }

    private function renderExceededMessage(): string
    {
        $template = $this->parseOption('template');
        if (! \is_string($template)) {
            return 'Rate limit exceeded!';
        }

        /** @var StandaloneView $view */
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename($template);
        $view->assignMultiple([
            'formIdentifier' => $this->finisherContext->getFormRuntime()->getIdentifier(),
            'policy' => $this->policy,
            'interval' => $this->interval,
            'limit' => $this->limit,
        ]);

        return $view->render();
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
