.. include:: /Includes.rst.txt

.. index:: Events

.. _events:

=============
PSR-14 events
=============

Target group: **Developers**

Have a look into the :ref:`event dispatcher documentation <t3coreapi:EventDispatcher>`,
if you are not familiar with PSR-14 events.

RateLimitExceededEvent
======================

.. versionadded:: 1.2.0

This event is dispatched when the rate limit for a form has been exceeded. This
way you can create an event listener which notifies you about the exceeded
limit: add a log entry, send an email, inform a third-party system, etc.

The event :php:`\Brotkrueml\FormRateLimit\Event\RateLimitExceededEvent`
provides the following methods:

:php:`->getFormIdentifier(): string`
   Returns the form identifier.

:php:`->getInterval(): string`
   Returns the configured :ref:`interval <options-interval>`.

:php:`->getLimit(): int`
   Returns the configured :ref:`limit <options-limit>`.

:php:`->getPolicy(): string`
   Returns the configured :ref:`policy <options-policy>`.

:php:`->getRequest(): \Psr\Http\Message\ServerRequestInterface`
   .. versionadded:: 1.3.0

   Returns the :ref:`PSR-7 request object <t3coreapi:typo3-request>`.

Example
-------

This example adds an entry to the TYPO3 log:

.. code-block:: php
   :caption: EXT:your_extension/Classes/EventListener/FormRateLimitExceededLogger.php

   <?php

   declare(strict_types=1);

   namespace YourVendor\YourExtension\EventListener;

   use Brotkrueml\FormRateLimit\Event\RateLimitExceededEvent;
   use Psr\Log\LoggerInterface;

   final class FormRateLimitExceededLogger
   {
       private LoggerInterface $logger;

       public function __construct(LoggerInterface $logger)
       {
           $this->logger = $logger;
       }

       public function __invoke(RateLimitExceededEvent $event): void
       {
           $this->logger->warning(
               'The form with identifier "{formIdentifier}" was sent more than {limit} times within {interval}',
               [
                   'formIdentifier' => $event->getFormIdentifier(),
                   'limit' => $event->getLimit(),
                   'interval' => $event->getInterval()
               ]
           );
       }
   }

Registration of the event listener:

.. code-block:: yaml
   :caption: EXT:your_extension/Configuration/Services.yaml

   services:
      # Place here the default dependency injection configuration

     YourVendor\YourExtension\EventListener\FormRateLimitExceededLogger:
       tags:
         - name: event.listener
           identifier: 'yourFormRateLimitExceededLogger'

Read :ref:`how to configure dependency injection in extensions <t3coreapi:dependency-injection-in-extensions>`.
