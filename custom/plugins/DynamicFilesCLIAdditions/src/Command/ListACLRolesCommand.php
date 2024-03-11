<?php declare(strict_types=1);

namespace DynamicFiles\Shopware\CLIAdditions\Command;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'cli-additions:system:acl-roles:list',
    description: 'List all available user roles.'
)]
class ListACLRolesCommand extends Command
{
    public function __construct(
        private readonly EntityRepository $aclRoleRepository
    ){
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('show-permissions', null, InputOption::VALUE_NONE, 'Print the configured permissions.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // empty criteria to get all
        $criteria = new Criteria();

        // is internal, but there is no other way https://shopwaredaily.com/blog/shopware-6-get-context-scheduled-task-cli/
        $context = Context::createDefaultContext();
        $existingACLRolesCollection = $this->aclRoleRepository->search($criteria, $context)->getEntities();

        if( $existingACLRolesCollection->count() > 0){
            $table = new Table($output);
            if ($input->getOption('show-permissions')){
                $table->setHeaders(['ID', 'Name', 'Permissions']);
            } else {
                $table->setHeaders(['ID', 'Name']);
            }
            foreach($existingACLRolesCollection->getElements() as $aclRole){
                if ($input->getOption('show-permissions')){
                    $table->addRow([$aclRole->id, $aclRole->name, implode("\n", $aclRole->privileges)]);
                } else {
                    $table->addRow([$aclRole->id, $aclRole->name]);
                }
            }
            $table->render();

            // for easier scripting
            $output->writeln("\nFound ". $existingACLRolesCollection->count() . " roles");
        } else {
            // for easier scripting
            $output->writeln("No roles found.");
        }

        // Exit code 0 for success
        return Command::SUCCESS;
    }
}