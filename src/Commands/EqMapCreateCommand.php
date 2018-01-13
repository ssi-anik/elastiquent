<?php namespace Anik\Elastiquent\Commands;

use Anik\Elastiquent\Database\MappingMigrationInterface;
use Anik\Elastiquent\Exceptions\InvalidArgumentException;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;

class EqMapCreateCommand extends Command
{
	private $composer, $files;

	public function __construct (Composer $composer, Filesystem $filesystem) {
		parent::__construct();
		$this->composer = $composer;
		$this->files = $filesystem;
	}

	protected $signature = 'eq:create-mapper {name : The name of the type mapper file.}
				{--index= : Give the index name.}
				{--type= : Give the type name.}';
	protected $description = 'Create a new mapper for a type.';

	public function handle () {
		// get the name of the file
		$name = trim($this->input->getArgument('name'));
		// check if the index name is given, or place a dummy index name
		$index = "YOUR_INDEX_HERE";
		if ($this->hasOption('index') && ($indexName = trim($this->input->getOption('index'))) && !empty($indexName)) {
			$index = $indexName;
		}
		// check if the type is given or not
		$type = $this->hasOption('type') ? trim($this->input->getOption('type')) : null;
		if (empty($type)) {
			// if the user input is something like create_(restaurants)_mapper
			// then we can guess `restaurants` can be the type name
			if (preg_match('/^create_(\w+)_mapper/i', $name, $matches)) {
				// found a match, so we can just lowercase the TYPE
				$type = strtolower($matches[1]);
			}
		}

		// build the class name from the name given
		$className = $this->getClassName($name);
		// check if there is any other file have the same class name like this.
		if ($this->checkIfClassAlreadyExists($className)) {
			throw new InvalidArgumentException("A class already exists {$className}");
		}
		// create the filename with timestamp so that it becomes unique
		$filename = sprintf("%s_%s.php", Carbon::now()->format("Y_m_d_His"), $name);
		$databaseRepository = app(MappingMigrationInterface::class);
		if (!$databaseRepository->isMigrationTableCreated()) {
			$this->call('eq:install');
		}
		// finally create the mapper file with stub
		$this->createMapperFile($filename, $className, $index, $type);
	}

	private function createMapperFile ($filename, $className, $index, $type) {
		// based on the given TYPE, choose which stub to prepare
		if (!empty($type)) {
			$stubFile = "create.stub";
		} else {
			$stubFile = "blank.stub";
		}
		// get the stub string
		$dummyStubString = $this->files->get($this->stubPath() . DIRECTORY_SEPARATOR . $stubFile);
		// populate the stub to real data
		$changedStubString = $this->populateStub($dummyStubString, [
			'DummyClass'      => $className,
			'DummyType'       => $type,
			'YOUR_INDEX_HERE' => $index,
		]);
		// get the mapping dir
		$mappingDirectory = $this->getMappingStorageDirectory();
		// put the data to that file
		$this->files->put($mappingDirectory . DIRECTORY_SEPARATOR . $filename, $changedStubString);
		// show information
		$this->line("<info>Created Mapping:</info> {$filename}");
		// autoload the composer
		$this->composer->dumpAutoloads();
	}

	private function getMappingStorageDirectory () {
		return mapping_path();
	}

	private function populateStub ($previous, $changes) {
		return str_replace(array_keys($changes), array_values($changes), $previous);
	}

	private function stubPath () {
		return __DIR__ . '/stubs';
	}

	private function getClassName ($name) {
		return studly_case($name);
	}

	private function checkIfClassAlreadyExists ($className) {
		return class_exists($className);
	}
}