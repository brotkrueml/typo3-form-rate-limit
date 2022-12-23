<?php

declare(strict_types=1);

/*
 * This file is part of the "form_rate_limit" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FormRateLimit\Command;

use Brotkrueml\FormRateLimit\RateLimiter\Storage\FileStorageCleaner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class CleanUpExpiredStorageEntriesCommand extends Command
{
    private FileStorageCleaner $fileStorageCleaner;

    public function __construct(FileStorageCleaner $fileStorageCleaner)
    {
        $this->fileStorageCleaner = $fileStorageCleaner;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $count = $this->fileStorageCleaner->cleanUp();

        if ($count->getErroneous() > 0) {
            $io->warning(\sprintf('%d files could not be deleted.', $count->getErroneous()));
        }

        $io->success(
            \sprintf(
                '%d expired files were deleted successfully, %d total files were available.',
                $count->getDeleted(),
                $count->getTotal()
            )
        );

        return Command::SUCCESS;
    }
}
