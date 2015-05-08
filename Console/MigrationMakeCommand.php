<?php namespace Illuminate\Workbench\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class MigrationMakeCommand extends GeneratorCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'workbench:migration';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new migration';

	/**
	 * The type of class being generated.
	 *
	 * @var string
	 */
	protected $type = 'Migration';

	/**
	 * Get the stub file for the generator.
	 *
	 * @return string
	 */
	protected function getStub() {
		return __DIR__ . '/../stubs/migrations/create.stub';
	}


	/**
	 * Get the destination class path.
	 *
	 * @param  string $name
	 * @return string
	 */
	protected function getPath($name) {
		$name = str_replace($this->getAppNamespace(), '', $name);

		return base_path()."/workbench/" . $this->option('bench') .  "/migrations/" . $this->getDatePrefix() . '_' . $name . ".php";
	}

	/**
	 * Get the date prefix for the migration.
	 *
	 * @return string
	 */
	protected function getDatePrefix()
	{
		return date('Y_m_d_His');
	}

	/**
	 * Build the class with the given name.
	 *
	 * @param  string $name
	 * @return string
	 */
	protected function buildClass($name) {
		$stub = $this->files->get($this->getStub());

		return $this->replaceTable($stub)
			->replaceClass($stub, $this->replaceClassName($name));
	}

	/**
	 * Replace table for the given stub.
	 *
	 * @param  string $stub
	 * @return $this
	 */
	protected function replaceTable(&$stub){
		$stub = str_replace('{{table}}', $this->option('create'), $stub);

		return $this;
	}

	/**
	 * Format file name for naming classs
	 *
	 * @param  string $name
	 * @return $this
	 */
	protected function replaceClassName($name) {
		$name = implode('', array_map('ucfirst', explode('_', $name)));
		$name = implode('\\', array_map('ucfirst', explode('\\', $name)));

		return $name;
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions() {
		return [
			['bench', null, InputOption::VALUE_REQUIRED, 'Which package to create migration'],
			['create', null, InputOption::VALUE_REQUIRED, 'Name of the table to create']
		];
	}

}