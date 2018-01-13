<?php namespace Anik\Elastiquent\Database;

use Illuminate\Database\DatabaseManager;

class MappingMigrationRepository implements MappingMigrationInterface
{
	private $database, $table, $connection;

	public function __construct (DatabaseManager $databaseManager, $connection, $table) {
		$this->database = $databaseManager;
		$this->connection = $connection;
		$this->table = $table;
	}

	public function createRepository () {
		$schema = $this->getConnection()->getSchemaBuilder();

		$schema->create($this->table, function ($table) {
			$table->increments('id');
			$table->string('migration');
			$table->integer('batch');
		});
	}

	public function isMigrationTableCreated () {
		$schema = $this->getConnection()->getSchemaBuilder();

		return $schema->hasTable($this->table);
	}

	public function getConnection () {
		return $this->database->connection($this->connection);
	}

	public function getMigrations () {
		dd($this->database->table($this->table)->get());
	}

	public function getLastBatch () {
		// TODO: Implement getLastBatch() method.
	}

	public function getMigrationsByBatch ($batch) {
		// TODO: Implement getMigrationsByBatch() method.
	}


}