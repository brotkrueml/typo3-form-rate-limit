<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Tests\Functional\Finisher;

use Brotkrueml\FormRateLimit\Domain\Finishers\RateLimitFinisher;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Stub;
use Symfony\Component\DependencyInjection\Container;
use TYPO3\CMS\Core\Http\NormalizedParams;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Form\Domain\Finishers\FinisherContext;
use TYPO3\CMS\Form\Domain\Runtime\FormRuntime;
use TYPO3\CMS\Form\Service\TranslationService;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversClass(RateLimitFinisher::class)]
final class RateLimitFinisherTest extends FunctionalTestCase
{
    protected array $coreExtensionsToLoad = [
        'typo3/cms-form',
    ];

    protected array $testExtensionsToLoad = [
        'brotkrueml/typo3-form-rate-limit',
    ];

    #[Test]
    public function onFirstExecutionSubmittingTheFormSubmissionSucceeds(): void
    {
        $this->mockTranslationService();
        /** @var RateLimitFinisher $subject */
        $subject = $this->get(RateLimitFinisher::class);

        $actual = $subject->execute(
            new FinisherContext($this->createMock(FormRuntime::class), $this->getRequestStub('127.0.0.1')),
        );

        self::assertNull($actual);
    }

    #[Test]
    public function onSecondExecutionSubmittingTheFormReturnsAnErrorIfLimitItSetTo1(): void
    {
        $this->mockTranslationService();
        /** @var RateLimitFinisher $subject */
        $subject = $this->get(RateLimitFinisher::class);
        $subject->setOption('limit', 1);

        $actual = $subject->execute(
            new FinisherContext($this->createMock(FormRuntime::class), $this->getRequestStub('127.0.0.2')),
        );
        self::assertNull($actual);

        $actual = $subject->execute(
            new FinisherContext($this->createMock(FormRuntime::class), $this->getRequestStub('127.0.0.2')),
        );
        self::assertStringContainsString('The rate limit has been exceeded.', (string) $actual);
    }

    #[Test]
    public function onThirdExecutionSubmittingTheFormReturnsAnErrorIfLimitItSetTo2(): void
    {
        $this->mockTranslationService();
        /** @var RateLimitFinisher $subject */
        $subject = $this->get(RateLimitFinisher::class);
        $subject->setOption('limit', 2);

        $actual = $subject->execute(
            new FinisherContext($this->createMock(FormRuntime::class), $this->getRequestStub('127.0.0.3')),
        );
        self::assertNull($actual);

        $actual = $subject->execute(
            new FinisherContext($this->createMock(FormRuntime::class), $this->getRequestStub('127.0.0.3')),
        );
        self::assertNull($actual);

        $actual = $subject->execute(
            new FinisherContext($this->createMock(FormRuntime::class), $this->getRequestStub('127.0.0.3')),
        );
        self::assertStringContainsString('The rate limit has been exceeded.', (string) $actual);
    }

    #[Test]
    public function onConsecutiveExecutionOutsideTheGivenIntervalTheFormSubmissionSucceeds(): void
    {
        $this->mockTranslationService();
        /** @var RateLimitFinisher $subject */
        $subject = $this->get(RateLimitFinisher::class);
        $subject->setOptions([
            'limit' => 1,
            'interval' => '1 second',
        ]);

        $actual = $subject->execute(
            new FinisherContext($this->createMock(FormRuntime::class), $this->getRequestStub('127.0.0.4')),
        );
        \sleep(1);
        $actual = $subject->execute(
            new FinisherContext($this->createMock(FormRuntime::class), $this->getRequestStub('127.0.0.4')),
        );

        self::assertNull($actual);
    }

    #[Test]
    public function passingACustomTemplateIsUsedOnErrorIfGiven(): void
    {
        $this->mockTranslationService();
        /** @var RateLimitFinisher $subject */
        $subject = $this->get(RateLimitFinisher::class);
        $subject->setOptions([
            'limit' => 1,
            'template' => 'EXT:form_rate_limit/Tests/Functional/Fixtures/RateLimitExceeded.html',
        ]);

        $actual = $subject->execute(
            new FinisherContext($this->createMock(FormRuntime::class), $this->getRequestStub('127.0.0.5')),
        );
        $actual = $actual = $subject->execute(
            new FinisherContext($this->createMock(FormRuntime::class), $this->getRequestStub('127.0.0.5')),
        );

        self::assertStringContainsString('Custom template for exceeded rate limit', (string) $actual);
    }

    private function getRequestStub(string $remoteAddress): Request&Stub
    {
        $normalizesParams = new NormalizedParams(
            [
                'REMOTE_ADDR' => $remoteAddress,
            ],
            [],
            '',
            '',
        );

        $requestStub = self::createStub(Request::class);
        $requestStub
            ->method('getAttribute')
            ->with('normalizedParams')
            ->willReturn($normalizesParams);

        return $requestStub;
    }

    private function mockTranslationService(): void
    {
        /** @var Container $container */
        $container = $this->get('service_container');

        // Define a TranslationService mock which skips all the translation but simply returns the $optionValue
        // without any further processing.
        $translationServiceMock = $this->createMock(TranslationService::class);
        $translationServiceMock->method('translateFinisherOption')->willReturnCallback(static fn(): string => \func_get_arg(3));
        $container->set(TranslationService::class, $translationServiceMock);
    }
}
