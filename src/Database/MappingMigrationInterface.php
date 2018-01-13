<?php

namespace Anik\Elastiquent\Database;

interface MappingMigrationInterface
{
	public function getMigrations ();

	public function getLastBatch ();

	public function getMigrationsByBatch ($batch);

	public function createRepository ();

	public function isMigrationTableCreated ();
}