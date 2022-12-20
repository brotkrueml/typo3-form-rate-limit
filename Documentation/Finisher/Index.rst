.. include:: /Includes.rst.txt

.. _finisher:

========
Finisher
========

Target group: **Integrators**

For now, the finisher can only be integrated directly into the YAML form
definition and not via the Forms module.

.. note::
   The :yaml:`RateLimit` finisher should be the first finisher in line.

Under the hood, the `symfony/rate-limiter`_ is used. This is the same rate
limiter that TYPO3 Core uses to limit the number of incorrect backend logins.

.. _symfony/rate-limiter: https://symfony.com/doc/current/rate_limiter.html


Example
=======

Let's start with an example:

.. code-block:: yaml

   finishers:
     - identifier: RateLimit
       options:
         policy: 'sliding_window'
         interval: '1 hour'
         limit: 2
         restrictions:
           - '__ipAddress'
           - '__formIdentifier'
           - '{email}'

     # other finishers follow

The example uses the "sliding window" :ref:`policy <options-policy>`. This form
can be submitted successfully twice within one hour from the same IP address
with the same provided email address.


Options
=======

The form finisher ships with default options. If they are suitable for your
needs, you can omit them in your form definition. The default values can be
found in the following list.

.. _options-interval:
.. confval:: interval

   :Type: string
   :Default: :yaml:`1 hour`

   The interval for the :ref:`policy <options-policy>` used. Since the interval
   is later passed to the `DateInterval`_ object, any possible value understood
   by that object is possible, for example:

   -  :yaml:`2 hours`
   -  :yaml:`10 minutes`
   -  :yaml:`1 day`
   -  :yaml:`1 hour 30 minutes`
   -  :yaml:`90 minutes`
   -  :yaml:`PT3600S` (3600 seconds)

   .. _DateInterval: https://www.php.net/manual/en/dateinterval.createfromdatestring.php#refsect1-dateinterval.createfromdatestring-examples

.. _options-limit:
.. confval:: limit

   :Type: int
   :Default: :yaml:`1`

   The limit to which the form can be submitted with the provided restrictions
   within the specified :ref:`interval <options-interval>`.

.. _options-policy:
.. confval:: policy

   :Type: string
   :Default: :yaml:`sliding_window`

   The policy. Two policies are currently supported:

   :yaml:`fixed_window`
      This is the simplest technique and it is based on setting a limit for a
      given interval of time (for instance, 5 submits per hour).

      See `Fixed Window Rate Limiter`_ for details.

   :yaml:`sliding_window`
      This is similar to the fixed window rate limiter, but then using a 1
      hour window that slides over the timeline.

      See: `Sliding Window Rate Limiter`_  for details.

   .. _Fixed Window Rate Limiter: https://symfony.com/doc/current/rate_limiter.html#fixed-window-rate-limiter
   .. _Sliding Window Rate Limiter: https://symfony.com/doc/current/rate_limiter.html#sliding-window-rate-limiter

.. _options-restrictions:
.. confval:: restrictions

   :Type: array
   :Default: :yaml:`['__ipAddress', '__formIdentifier']`

   The restrictions for limiting the submission of a form. The default value
   combines the IP address and the form identifier.

   The possible values can be combined at will:

   :yaml:`__ipAddress`
      The IP address is taken into account for limiting the submission of a
      form.

   :yaml:`__formIdentifier`
      The form identifier is taken into account. This is a combination of the
      identifier in the form definition and the content element ID.

      In connection mode the content element ID is the same for each language.
      In free mode the content element ID is different for each language.

   :yaml:`{aFormField}`
      Every form field can be used to add an additional restriction. The field
      identifier must be surrounded by curly brackets.

      For example, if you want to limit the submission to the email address,
      this field can be added to the restriction list as :yaml:`{email}`
      if the identifier of the email address field is named :yaml:`email`.
