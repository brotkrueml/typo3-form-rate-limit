.. include:: /Includes.rst.txt


.. _configuration:

=============
Configuration
=============

Target group: **Developers, Integrators**

.. contents::
   :local:

.. _include-static-typoscript:

Include static TypoScript
=========================

The extension ships some TypoScript code which needs to be included.

.. note::
   This needs only to be done, if **not** using TYPO3 v13 with
   :ref:`site sets <site-sets>`.

#. Switch to the root page of your site.

#. Switch to the :guilabel:`Template module` and select :guilabel:`Info/Modify`.

#. Press the link :guilabel:`Edit the whole template record` and switch to the
   tab :guilabel:`Includes`.

#. Select :guilabel:`Form Rate Limit (form_rate_limit)` from the
   available items at the field :guilabel:`Include static (from extensions):`

.. figure:: /Images/include-static-template.png
   :alt: Include static TypoScript

   Include static TypoScript


.. _site-sets:

Site sets (TYPO3 v13)
=====================

This extension provides support for :ref:`site sets <t3coreapi:site-sets>`
introduced with TYPO3 v13.1.

Add :yaml:`brotkrueml/form-rate-limit` as dependency to the configuration of
your site package:

.. code-block:: yaml
   :caption: EXT:your_sitepackage/Configuration/Sets/<your-set>/config.yaml
   :emphasize-lines: 7

   name: your-vendor/your-sitepackage
   label: Sitepackage

   dependencies:
     # ... some other dependencies

     - brotkrueml/form-rate-limit
