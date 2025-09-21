<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Form Rate Limit',
    'description' => 'TYPO3 form finisher for rate limiting when sending a form',
    'category' => 'fe',
    'author' => 'Chris Müller',
    'author_email' => 'typo3@brotkrueml.dev',
    'state' => 'stable',
    'version' => '2.0.0-dev',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.4.99',
            'fluid' => '12.4.0-13.4.99',
            'form' => '12.4.0-13.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => ['Brotkrueml\\FormRateLimit\\' => 'Classes']
    ],
];
