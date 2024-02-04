.. include:: /Includes.rst.txt

.. _installation:

============
Installation
============

Target group: **Administrators**

.. note::
   The extension in version |version| supports TYPO3 v11 LTS, TYPO3 v12 LTS and
   TYPO3 v13.

The recommended way to install this extension is by using Composer. In your
Composer-based TYPO3 project root, just type:

.. code-block:: bash

   composer req brotkrueml/typo3-form-rate-limit

and the recent stable version will be installed.

You can also install the extension from the `TYPO3 Extension Repository (TER)`_.
See :ref:`t3start:extensions_legacy_management` for a manual how to
install an extension.

.. _TYPO3 Extension Repository (TER): https://extensions.typo3.org/extension/form_rate_limit


.. _include-static-typoscript:

Preparation: Include static TypoScript
======================================

The extension ships some TypoScript code which needs to be included.

#. Switch to the root page of your site.

#. Switch to the :guilabel:`Template module` and select :guilabel:`Info/Modify`.

#. Press the link :guilabel:`Edit the whole template record` and switch to the
   tab :guilabel:`Includes`.

#. Select :guilabel:`Form Rate Limit (form_rate_limit)` from the
   available items at the field :guilabel:`Include static (from extensions):`

.. figure:: /Images/include-static-template.png
   :alt: Include static TypoScript

   Include static TypoScript
