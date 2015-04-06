<?php namespace Illuminate\Workbench\Helpers;

class Config {

	private $location;

	private $object;

	function __construct(){
		$this->location = base_path().'/workbench/{{vendor}}/{{package}}/config/{{file}}.php';
	}

	public function getConfig($vendor, $config) {
		$package = explode("::", $config);
		$file = explode(".", $package[1]);
		$this->object = require $this->setPath($vendor, $package[0], $file[0]);
		return $this->object[$file[1]];
	}

	private function setPath($vendor, $package, $file){
		$this->replaceVendor($vendor)->replacePackage($package)->replaceFile($file);

		return $this->location;
	}

	private function replaceVendor($vendor){
		$this->location = str_replace('{{vendor}}', $vendor, $this->location);

		return $this;
	}

	private function replacePackage($package){
		$this->location = str_replace('{{package}}', $package, $this->location);

		return $this;
	}

	private function replaceFile($file){
		$this->location = str_replace('{{file}}', $file, $this->location);

		return $this;
	}
}