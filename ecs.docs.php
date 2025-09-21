<?php

declare (strict_types=1);

use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $config): void {
    $header = <<<HEADER
This file is part of the "form_rate_limit" extension for TYPO3 CMS.

For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
HEADER;

    $config->import(__DIR__ . '/.Build/vendor/brotkrueml/coding-standards/config/common.php');

    $config->paths([
        __DIR__ . '/Documentation',
    ]);
    $config->ruleWithConfiguration(HeaderCommentFixer::class, [
        'comment_type' => 'comment',
        'header' => $header,
        'separate' => 'both',
    ]);
};
