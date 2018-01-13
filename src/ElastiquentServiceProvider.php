<?php namespace Anik\Elastiquent;

use Anik\Elastiquent\Commands\EqInstallCommand;
use Anik\Elastiquent\Database\MappingMigrationInterface;
use Anik\Elastiquent\Database\MappingMigrationRepository;
use Illuminate\Support\ServiceProvider;

class ElastiquentServiceProvider extends ServiceProvider
{
	private $availableCommands = [
		EqInstallCommand::class,
	];

	public function boot () {
		$this->app->bind(MappingMigrationInterface::class, function () {
			$config = app('config');
			$table = $config->get('elastiquent.migration');
			$connection = $config->get('elastiquent.connection');
			$database = app('db');

			return new MappingMigrationRepository($database, $connection, $table);
		});
		$this->registerCommands();
		$this->mergeConfigFrom(__DIR__ . '/config/elastiquent.php', 'elastiquent');
	}


	public function register () {
		$this->publishes([
			__DIR__ . "config/elastiquent.php" => config_path('elastiquent.php'),
		]);
	}

	private function registerCommands () {
		$this->commands($this->availableCommands);
	}
}