<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Domain\Finishers;

use Brotkrueml\FormRateLimit\Domain\Dto\Options;
use Brotkrueml\FormRateLimit\Event\RateLimitExceededEvent;
use Brotkrueml\FormRateLimit\Guards\IntervalGuard;
use Brotkrueml\FormRateLimit\Guards\LimitGuard;
use Brotkrueml\FormRateLimit\Guards\PolicyGuard;
use Brotkrueml\FormRateLimit\Guards\RestrictionsGuard;
use Brotkrueml\FormRateLimit\RateLimiter\FormRateLimitFactory;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Http\NormalizedParams;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher;

final class RateLimitFinisher extends AbstractFinisher
{
    private FormRateLimitFactory $rateLimitFactory;
    private EventDispatcherInterface $eventDispatcher;
    private IntervalGuard $intervalGuard;
    private LimitGuard $limitGuard;
    private PolicyGuard $policyGuard;
    private RestrictionsGuard $restrictionGuard;

    /**
     * @var array<string, string|int|string[]>
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

    public function __construct(FormRateLimitFactory $rateLimitFactory, EventDispatcherInterface $eventDispatcher)
    {
        $this->rateLimitFactory = $rateLimitFactory;
        $this->eventDispatcher = $eventDispatcher;
        $this->intervalGuard = new IntervalGuard();
        $this->limitGuard = new LimitGuard();
        $this->policyGuard = new PolicyGuard();
        $this->restrictionGuard = new RestrictionsGuard();
    }

    protected function executeInternal(): ?string
    {
        $options = new Options(
            $this->intervalGuard->guard($this->parseOption('interval')),
            $this->limitGuard->guard($this->parseOption('limit')),
            $this->policyGuard->guard($this->parseOption('policy')),
            $this->restrictionGuard->guard($this->parseOption('restrictions')),
        );

        /** @var NormalizedParams $normalizedParams */
        $normalizedParams = $this->finisherContext->getRequest()->getAttribute('normalizedParams');
        $limiter = $this->rateLimitFactory->createRateLimiter(
            $options,
            $this->finisherContext->getFormRuntime()->getIdentifier(),
            $normalizedParams->getRemoteAddress()
        );
        if (! $limiter->consume()->isAccepted()) {
            $this->eventDispatcher->dispatch(
                new RateLimitExceededEvent(
                    $this->finisherContext->getFormRuntime()->getIdentifier(),
                    $options,
                    $this->finisherContext->getRequest()
                )
            );
            $this->finisherContext->cancel();

            return $this->renderExceededMessage($options);
        }

        return null;
    }

    private function renderExceededMessage(Options $options): string
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
            'interval' => $options->getInterval(),
            'limit' => $options->getLimit(),
            'policy' => $options->getPolicy(),
        ]);

        return $view->render();
    }
}
