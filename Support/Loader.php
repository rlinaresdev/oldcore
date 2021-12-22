<?php
namespace Malla\Core\Support;

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/


use Illuminate\Foundation\AliasLoader;

class Loader {

	protected static $app;

	public function __construct( $app ) {
		self::$app = $app;
	}

	public function isAppStart( $type = null, $slug=null ) {

		if( (env("DB_HOST") == "127.0.0.1") && (env("DB_DATABASE") == "laravel") ) {
			return FALSE;
		}

		if( \Schema::hasTable("apps") ) {

			if(self::$app["malla"]->load("coredb")->has("core", "core")) {
				return (self::$app["malla"]->load("coredb")->get("core", "core")->activated == 1);
			}

		}

		return FALSE;
	}

	/*
	* ALIASES
	* Load Alias */
	public function loadAlias($alias=NULL) {

		if(!empty($alias) && is_array($alias)) {
			foreach ($alias as $alia => $class) {
				AliasLoader::getInstance()->alias($alia, $class);
			}
		}
	}

	/*
	* PROVIDERS
	* Load ServiceProvider */
	public function loadProviders($providers=[])
	{
		if(empty($providers)) return NULL;

		if(!is_array($providers)) $providers = [$providers];

		foreach ($providers as $provider)
		{
			self::$app->register($provider);
		}
	}

	/*
	* KERNEL
	* Load Packages */
	public function run($kernel=NULL) {

		if( !empty($kernel) ) {

			if( is_string($kernel) ) {
				$kernel = new $kernel;
			}

			## [0]
			if( method_exists($kernel, "handler") ) $kernel->handler( self::$app );

			## [1]
			if( method_exists($kernel, "providers") ) $this->loadProviders( $kernel->providers() );

			## [2]
			if( method_exists($kernel, "alias") ) $this->loadAlias( $kernel->alias() );

			return $kernel;
		}

		abort(500, "Error kernel packages");
	}

	public function register($type=null) {

		if( in_array($type, ["core", "library", "package", "plugin"]) ) {

			if( !empty( $stors = self::$app["malla"]->load("coredb")->getType($type) ) ) {

				foreach ($stors as $app ) {
					if($app->activated == 1) {
						/*
						* LOAD APP RESOURCES */
						if( !empty( ($configs = self::$app["malla"]->load("coredb")->getConfig($type, $app)) ) ) {
							foreach ( $configs as $config ) {
								config()->set($config->key, $config->value);
							}
						}

						/*
						* LOAD APP KERNEL */
						$this->run($app->kernel);
					}
				}
			}
		}
	}
}
