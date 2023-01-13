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

*  one IP address over all forms using this finisher
*  a combination of IP address and specific form
*  a combination of a specific form and an email address

If the limit is exceeded, a customizable error is displayed.


Release Management
==================

This extension uses `semantic versioning`_ which basically means for you, that

*  Bugfix updates (e.g. 1.0.0 => 1.0.1) just includes small bug fixes or
   security relevant stuff without breaking changes.
*  Minor updates (e.g. 1.0.0 => 1.1.0) includes new features and smaller tasks
   without breaking changes.
*  Major updates (e.g. 1.0.0 => 2.0.0) breaking changes which can be
   refactorings, features or bug fixes.

.. _semantic versioning: https://semver.org/
