<?php declare(strict_types=1);

namespace DynamicFiles\Shopware\CLIAdditions\Command;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\UsageData\Consent\BannerService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'cli-additions:system:usage-data:skip-for-user',
    description: 'TODO'
)]
class SkipUsageDataSharingForUserCommand extends Command
{
    public function __construct(
        private readonly BannerService $bannerService,
        private readonly EntityRepository $userRepository
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('name', null, InputOption::VALUE_REQUIRED, 'User name.');
        $this->addOption('id', null, InputOption::VALUE_REQUIRED, 'User ID.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // is internal, but there is no other way https://shopwaredaily.com/blog/shopware-6-get-context-scheduled-task-cli/
        $context = Context::createDefaultContext();

        $criteria = new Criteria();

        // TODO
        $username = $input->getOption('name');
        $userid = $input->getOption('id');

        $providedOptions = 0;
        // check if user exists
        if ($username) {
            $criteria->addFilter(new EqualsFilter('username', $username));
            $providedOptions += 1;
        }
        if ($userid) {
            $criteria->addFilter(new EqualsFilter('id', $userid));
            $providedOptions += 1;
        }

        if ($providedOptions == 0) {
            $output->writeln("You need to provide username or user ID.");
            return Command::FAILURE;
        }

        $user = $this->userRepository->search($criteria, $context)->first();
        if ($user){
            $this->bannerService->hideConsentBannerForUser($user->getUniqueIdentifier(), $context);
            return Command::SUCCESS;
        }

        $output->writeln("User not found.");
        return Command::FAILURE;
    }
}
