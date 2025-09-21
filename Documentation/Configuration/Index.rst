.. include:: /Includes.rst.txt


.. _configuration:

=============
Configuration
=============

Target group: **Developers, Integrators**

.. contents::
   :local:

.. _site-sets:

Site sets
=========

This extension provides support for :ref:`site sets <t3coreapi:site-sets>`.

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


.. _include-typoscript-sets:

Include TypoScript sets
=======================

The extension ships some TypoScript code which needs to be included.

.. note::
   This is only necessary, if **not** using :ref:`site sets <site-sets>`.

#. Switch to the root page of your site.

#. Switch to the :guilabel:`Site Management > TypoScript` module and select
   :guilabel:`Edit TypoScript record`.

#. Press the link :guilabel:`Edit the whole TypoScript record` and switch to the
   tab :guilabel:`Advanced Options`.

#. Select :guilabel:`Form Rate Limit (form_rate_limit)` from the
   available items at the field :guilabel:`Include TypoScript sets:`

.. figure:: /Images/include-typoscript-sets.png
   :alt: Include TypoScript sets

   Include TypoScript sets
