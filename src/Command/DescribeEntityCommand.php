<?php
// src/Command/DescribeEntityCommand.php
namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:describe-entity',
    description: 'Describes a Doctrine entity',
)]
class DescribeEntityCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('entity', InputArgument::REQUIRED, 'The entity class name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $entityClass = $input->getArgument('entity');

        try {
            $metadata = $this->entityManager->getClassMetadata($entityClass);

            $io->title('Entity: '.$metadata->getName());

            $io->section('Identifier(s)');
            $io->listing($metadata->getIdentifierFieldNames());

            $io->section('Field Mappings');
            $fields = [];
            foreach ($metadata->getFieldNames() as $fieldName) {
                $fields[] = [
                    $fieldName,
                    $metadata->getTypeOfField($fieldName),
                    $metadata->isNullable($fieldName) ? 'Yes' : 'No',
                ];
            }
            $io->table(['Field', 'Type', 'Nullable'], $fields);

            $io->section('Association Mappings');
            $associations = [];
            foreach ($metadata->getAssociationMappings() as $name => $mapping) {
                $associations[] = [
                    $name,
                    $mapping['targetEntity'],
                    $mapping['type'] === 1 ? 'OneToOne' :
                        ($mapping['type'] === 2 ? 'OneToMany' : 'ManyToMany'),
                    $mapping['isOwningSide'] ? 'Yes' : 'No',
                ];
            }
            $io->table(['Association', 'Target', 'Type', 'Owning Side'], $associations ?? [['No associations', '', '', '']]);

        } catch (\Doctrine\Persistence\Mapping\MappingException $e) {
            $io->error(sprintf('Entity "%s" does not exist or is not mapped.', $entityClass));
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}