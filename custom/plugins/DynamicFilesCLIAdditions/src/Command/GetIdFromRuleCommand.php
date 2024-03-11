<?php declare(strict_types=1);

namespace DynamicFiles\Shopware\CLIAdditions\Command;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'cli-additions:rules:get-id',
    description: 'Get ID of rule with given name.'
)]
class GetIdFromRuleCommand extends Command
{
    public function __construct(
        private readonly EntityRepository $ruleRepository
    ){
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('rule-name', InputOption::VALUE_REQUIRED, 'The name of the rule you want to get the ID from.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // empty criteria to get all
        $criteria = new Criteria();
        $criteria->addFields(["id"]);
        $criteria->addFilter(new EqualsFilter('name', $input->getArgument('rule-name')));
        $criteria->setLimit(1);

        // is internal, but there is no other way https://shopwaredaily.com/blog/shopware-6-get-context-scheduled-task-cli/
        $context = Context::createDefaultContext();
        $existingRule = $this->ruleRepository->search($criteria, $context)->first();

        if( $existingRule ){
            // for easier scripting
            $output->writeln($existingRule->getUniqueIdentifier());

            return Command::SUCCESS;
        }

        // for easier scripting
        $output->writeln("No rule found.");

        return Command::FAILURE;
    }
}
