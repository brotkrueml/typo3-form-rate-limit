.. include:: /Includes.rst.txt

.. _troubleshooting:

===============
Troubleshooting
===============

-  **The finisher preset identified by "RateLimit" could not be found, or the
   implementationClassName was not specified.**:

   Please include the :ref:`static TypoScript file <include-static-typoscript>`.

-  **Tried resolving a template file for controller action "Standard->index" in
   format ".html", but none of the paths contained the expected template file
   (...). No paths configured.**

   The :ref:`assigned template <options-template>` to the finisher is not
   available. Please check the path and filename.
