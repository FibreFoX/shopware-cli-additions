<?php declare(strict_types=1);

namespace DynamicFiles\Shopware\CLIAdditions\Command\System;

use Shopware\Core\System\UsageData\Consent\ConsentService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'cli-additions:system:usage-data:revoke',
    description: 'Revoke/Decline the terms for system usage data sharing.'
)]
class RevokeUsageDataSharingCommand extends Command
{
    public function __construct(
        private readonly ConsentService $consentService
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->consentService->revokeConsent();
        return Command::SUCCESS;
    }
}
