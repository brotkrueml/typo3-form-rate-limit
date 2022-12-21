<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Domain\Finishers;

use Brotkrueml\FormRateLimit\Dto\Options;
use Brotkrueml\FormRateLimit\Guards\IntervalGuard;
use Brotkrueml\FormRateLimit\Guards\LimitGuard;
use Brotkrueml\FormRateLimit\Guards\PolicyGuard;
use Brotkrueml\FormRateLimit\RateLimiter\FormRateLimitFactory;
use TYPO3\CMS\Core\Http\NormalizedParams;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher;

final class RateLimitFinisher extends AbstractFinisher
{
    private FormRateLimitFactory $rateLimitFactory;
    private IntervalGuard $intervalGuard;
    private LimitGuard $limitGuard;
    private PolicyGuard $policyGuard;

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

    public function __construct(FormRateLimitFactory $rateLimitFactory)
    {
        $this->rateLimitFactory = $rateLimitFactory;
        $this->intervalGuard = new IntervalGuard();
        $this->limitGuard = new LimitGuard();
        $this->policyGuard = new PolicyGuard();
    }

    protected function executeInternal(): ?string
    {
        $options = new Options(
            $this->intervalGuard->guard($this->parseOption('interval')),
            $this->limitGuard->guard($this->parseOption('limit')),
            $this->policyGuard->guard($this->parseOption('policy')),
            $this->parseOption('restrictions')
        );

        /** @var NormalizedParams $normalizedParams */
        $normalizedParams = $this->finisherContext->getRequest()->getAttribute('normalizedParams');
        $limiter = $this->rateLimitFactory->createRateLimiter(
            $options,
            $this->finisherContext->getFormRuntime()->getIdentifier(),
            $normalizedParams->getRemoteAddress()
        );
        if (! $limiter->consume()->isAccepted()) {
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
