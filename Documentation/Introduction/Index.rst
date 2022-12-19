.. include:: /Includes.rst.txt

.. _introduction:

============
Introduction
============

.. _what-it-does:

What does it do?
================

The extension provides a form finisher for the :doc:`TYPO3 Form Framework
<ext_form:Index>` for rate limiting when sending a form. This way a submission
can be restricted to, for example:

-  one IP address over all forms using this finisher
-  a combination of IP address and specific form
-  a combination of a specific form and an email address

If the limit is exceeded, a customizable error is displayed.


Release Management
==================

This extension uses `semantic versioning`_ which basically means for you, that

* Bugfix updates (e.g. 1.0.0 => 1.0.1) just includes small bug fixes or security
  relevant stuff without breaking changes.
* Minor updates (e.g. 1.0.0 => 1.1.0) includes new features and smaller tasks
  without breaking changes.
* Major updates (e.g. 1.0.0 => 2.0.0) breaking changes which can be
  refactorings, features or bug fixes.


.. _ISO 3166-1: https://en.wikipedia.org/wiki/ISO_3166-1
.. _alpha-2 code: https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
.. _English form: https://www.jobrouter.com/en/contact/#contact-6
.. _German form: https://www.jobrouter.com/de/kontakt/#contact-2454
.. _ICU library: https://github.com/unicode-org/icu
.. _Polish form: https://www.jobrouter.com/pl/kontakt/#contact-3657
.. _semantic versioning: https://semver.org/
.. _symfony/intl: https://github.com/symfony/intl
.. _Turkish form: https://www.jobrouter.com/tr/iletisim/#contact-3648
