<?php namespace Illuminate\Workbench;

use Illuminate\Support\ServiceProvider;
use Illuminate\Workbench\Console\WorkbenchMakeCommand;

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
		$this->app->singleton('package.creator', function ($app) {
			return new PackageCreator($app['files']);
		});

		$this->app->singleton('command.workbench', function ($app) {
			return new WorkbenchMakeCommand($app['package.creator']);
		});

		$this->commands('command.workbench');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		return ['package.creator', 'command.workbench'];
	}

}
