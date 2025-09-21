.. include:: /Includes.rst.txt

.. index:: Events

.. _events:

============
PSR-14 event
============

Target group: **Developers**

Have a look into the :ref:`event dispatcher documentation <t3coreapi:EventDispatcher>`,
if you are not familiar with PSR-14 events.

RateLimitExceededEvent
======================

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
   Returns the :ref:`PSR-7 request object <t3coreapi:typo3-request>`.

Example
-------

This example adds an entry to the TYPO3 log:

.. literalinclude:: _FormRateLimitExceededLogger.php
   :caption: EXT:your_extension/Classes/EventListener/FormRateLimitExceededLogger.php
