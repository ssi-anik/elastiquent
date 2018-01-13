<?php namespace Anik\Elastiquent\Commands;

class EqInstallCommand extends BaseCommand
{
	protected $signature = "eq:install";

	protected $description = "Install elastiquent mappings table.";

	public function handle () {
		if (!$this->repository->isMigrationTableCreated()) {
			$this->repository->createRepository();
			$this->line("<info>EQ migrations table created.</info>");
		}

		$mappingDirectory = mapping_path();
		$this->createMappingDirectory($mappingDirectory);
	}


	private function createMappingDirectory ($directoryPath) {
		if (!file_exists($directoryPath)) {
			mkdir($directoryPath, 0777, true);
		}
	}
}