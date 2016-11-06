<?php namespace Illuminate\Workbench\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ControllerMakeCommand extends GeneratorCommand {

	use \Illuminate\Console\AppNamespaceDetectorTrait;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'workbench:controller';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new resource controller class';

	/**
	 * The type of class being generated.
	 *
	 * @var string
	 */
	protected $type = 'Controller';

	/**
	 * Get the stub file for the generator.
	 *
	 * @return string
	 */
	protected function getStub() {
		if ($this->option('plain')) {
			return __DIR__ . '/../stubs/controller/controller.plain.stub';
		}

		if ($this->option('extended')) {
			return __DIR__ . '/../stubs/controller/controller.extended.stub';
		}

		return __DIR__ . '/../stubs/controller/controller.stub';
	}


	/**
	 * Get the destination class path.
	 *
	 * @param  string $name
	 * @return string
	 */
	protected function getPath($name) {
		$name = str_replace($this->getAppNamespace(), '', $name);

		return base_path()."/workbench/" . $this->option('bench') .  "/app/Controllers/" . $name . ".php";
	}

	/**
	 * Build the class with the given name.
	 *
	 * @param  string $name
	 * @return string
	 */
	protected function buildClass($name) {
		$stub = $this->files->get($this->getStub());

		return $this->replaceNamespace($stub, $name)
			->replaceBench($stub)
			->replacePackage($stub)
			->replaceClass($stub, $name);
	}

	/**
	 * Replace bench for the given stub.
	 *
	 * @param  string $stub
	 * @return $this
	 */
	protected function replaceBench(&$stub) {
		$bench = str_replace('/', '\\', $this->option('bench'));
		$bench = implode('\\', array_map('ucfirst', explode('\\', $bench)));
		$stub = str_replace('{{bench}}', $bench, $stub);

		return $this;
	}

	/**
	 * Replace package for the given stub.
	 *
	 * @param  string $stub
	 * @return $this
	 */
	protected function replacePackage(&$stub){
		$package = explode('/', $this->option('bench'));
		$stub = str_replace('{{package}}', $package[1], $stub);

		return $this;
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions() {
		return [
			['bench', null, InputOption::VALUE_REQUIRED, 'Name of the workbench package (vendor/name).'],
			['plain', null, InputOption::VALUE_NONE, 'Generate an empty controller class.'],
			['extended', null, InputOption::VALUE_NONE, 'Generate an extended controller class.'],
		];
	}

}
