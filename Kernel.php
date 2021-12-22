<?php
namespace Malla\Core;

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/
use \ZipArchive;

class Kernel {
	public function providers() {
		return [
		];
	}

	public function alias() {
		return [
			"Zip" => \Malla\Core\Support\Zip::class,
		];
	}

	public function handler($app) {

		$app->bind("Zip", function($app) {
			return new \Malla\Core\Support\Zip($app, new ZipArchive());
		});

		$app["zip"] = \Malla\Core\Facade\Zip::load();
	}
}
