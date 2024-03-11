<?php declare(strict_types=1);

namespace DynamicFiles\Shopware\CLIAdditions\Command;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'cli-additions:rules:list',
    description: 'List all available rules.'
)]
class ListRulesCommand extends Command
{
    public function __construct(
        private readonly EntityRepository $ruleRepository
    ){
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // empty criteria to get all
        $criteria = new Criteria();

        // is internal, but there is no other way https://shopwaredaily.com/blog/shopware-6-get-context-scheduled-task-cli/
        $context = Context::createDefaultContext();
        $existingRulesCollection = $this->ruleRepository->search($criteria, $context)->getEntities();

        if( $existingRulesCollection->count() > 0){
            $table = new Table($output);
            $table->setHeaders(['ID', 'Name', 'Description', 'Priority']);
            foreach($existingRulesCollection->getElements() as $rule){
                $table->addRow([$rule->id, $rule->name, $rule->description, $rule->priority]);
            }
            $table->render();

            // for easier scripting
            $output->writeln("\nFound ". $existingRulesCollection->count() . " rules");
        } else {
            // for easier scripting
            $output->writeln("No rules found.");
        }

        // Exit code 0 for success
        return Command::SUCCESS;
    }
}