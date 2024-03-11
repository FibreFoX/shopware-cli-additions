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
    name: 'cli-additions:system:acl-roles:list-role-permissions',
    description: 'List all permissions for given user role.'
)]
class ListACLRolePermissionsCommand extends Command
{
    public function __construct(
        private readonly EntityRepository $aclRoleRepository
    ){
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('user-group', InputOption::VALUE_REQUIRED, 'The ID of the ACL role you want to get the permissions from.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // empty criteria to get all
        $criteria = new Criteria();
        $criteria->addFields(["privileges"]);
        $criteria->addFilter(new EqualsFilter('id', $input->getArgument('user-group')));
        $criteria->setLimit(1);

        // is internal, but there is no other way https://shopwaredaily.com/blog/shopware-6-get-context-scheduled-task-cli/
        $context = Context::createDefaultContext();
        $existingACLRole = $this->aclRoleRepository->search($criteria, $context)->first();

        if( $existingACLRole ){
            $table = new Table($output);
            $table->setHeaders(['Permission']);
            foreach($existingACLRole->getPrivileges() as $permission){
                $table->addRow([$permission]);
            }
            $table->render();

            // for easier scripting
            $output->writeln("\nFound ". count($existingACLRole->getPrivileges()) . " permissions");

            return Command::SUCCESS;
        }

        // for easier scripting
        $output->writeln("No role found.");

        return Command::FAILURE;
    }
}
