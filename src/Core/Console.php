<?php
namespace Neochic\SlimTools\Core;

use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Helper\DialogHelper;
use Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

class Console extends Bootable
{
    protected $em;

    public function __construct(array $config, EntityManager $em)
    {
        parent::__construct($config);
        $this->em = $em;
    }

    public function run()
    {
        $helperset =new HelperSet(array(
            'db' => new ConnectionHelper($this->em->getConnection()),
            'em' => new EntityManagerHelper($this->em),
            'dialog' => new DialogHelper()
        ));

        $configuration = new Configuration($this->em->getConnection());
        $configuration->setMigrationsDirectory($this->config['path']);
        $configuration->setMigrationsNamespace($this->config['namespace']);
        $configuration->setMigrationsTableName($this->config['table']);
        $configuration->setName($this->config['name']);
        $configuration->registerMigrationsFromDirectory($configuration->getMigrationsDirectory());

        $commands = array(
            new DiffCommand(),
            new ExecuteCommand(),
            new GenerateCommand(),
            new MigrateCommand(),
            new StatusCommand(),
            new VersionCommand()
        );

        foreach($commands as $command) {
            $command->setMigrationConfiguration($configuration);
        }

        ConsoleRunner::createApplication($helperset, $commands)->run();
    }
}