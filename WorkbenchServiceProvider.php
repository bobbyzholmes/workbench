<?php namespace Illuminate\Workbench;

use Illuminate\Support\ServiceProvider;
use Illuminate\Workbench\Console\WorkbenchMakeCommand;
use Illuminate\Workbench\Console\ControllerMakeCommand;
use Illuminate\Workbench\Console\MigrationMakeCommand;

class WorkbenchServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	public function boot() {
		$this->publishes([
			__DIR__.'/stubs/workbench.php' => config_path('workbench.php'),
		]);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->registerPackageCreator();
		$this->registerWorkbenchGenerator();
		$this->registerControllerGenerator();

		$this->registerMigrationGenerator();

		$this->commands(
			'command.workbench',
			'command.workbench.make',
			'command.workbench.migration'
		);
	}

	protected function registerPackageCreator(){
		$this->app->singleton('package.creator', function ($app) {
			return new PackageCreator($app['files']);
		});
	}

	protected function registerWorkbenchGenerator(){
		$this->app->singleton('command.workbench', function ($app) {
			return new WorkbenchMakeCommand($app['package.creator']);
		});
	}

	protected function registerControllerGenerator(){
		$this->app->singleton('command.workbench.make', function ($app) {
			return new ControllerMakeCommand($app['files']);
		});
	}

	protected function registerMigrationGenerator(){
		$this->app->singleton('command.workbench.migration', function ($app) {
			return new MigrationMakeCommand($app['files']);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		return [
			'package.creator',
			'command.workbench',
			'command.workbench.make',
			'command.workbench.migration'
		];
	}

}
