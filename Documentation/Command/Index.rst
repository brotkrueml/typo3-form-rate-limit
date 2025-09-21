.. include:: /Includes.rst.txt

.. _command:

=======
Command
=======

Target group: **Administrators**

The states of the rate limiter is stored in the :file:`var/form_rate_limit/`
folder. To clean up this folder with expired states, a command is available. You
can call it on the console with:

.. tabs::

   .. group-tab:: Composer mode

      .. code-block:: bash

         vendor/bin/typo3 formratelimit:cleanupexpiredstorageentries

   .. group-tab:: Classic mode

      .. code-block:: bash

         typo3/sysext/core/bin/typo3 formratelimit:cleanupexpiredstorageentries

To run the command regularly you can add a cron job or run it from the TYPO3 scheduler.
