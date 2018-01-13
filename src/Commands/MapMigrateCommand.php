<?php namespace Anik\Elastiquent\Commands;

use Illuminate\Console\Command;

class MapMigrateCommand extends Command
{
	protected $signature = "eq:map-migrate";

	protected $description = "Migrate the mappings into elasticsearch.";

	public function __construct () {

	}
}