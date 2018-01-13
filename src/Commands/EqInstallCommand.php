<?php namespace Anik\Elastiquent\Commands;

class EqInstallCommand extends BaseCommand
{
	protected $signature = "eq:install";

	protected $description = "Install elastiquent mappings table.";

	public function handle () {
		if (!$this->repository->isMigrationTableCreated()) {
			$this->repository->createRepository();
			$this->line("<info>EQ migrations table created.</info>");
		} else {
			$this->line("<info>Migrations table already created.</info>");
		}
	}
}