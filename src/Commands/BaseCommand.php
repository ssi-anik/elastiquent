<?php namespace Anik\Elastiquent\Commands;

use Anik\Elastiquent\Database\MappingMigrationInterface;
use Illuminate\Console\Command;

class BaseCommand extends Command
{
	protected $repository;

	public function __construct (MappingMigrationInterface $mappingMigration) {
		parent::__construct();
		$this->repository = $mappingMigration;
	}
}