<?php

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/

define("__MALLA__", realpath(__DIR__."/../../../")."/");

define("__HTTP__", __MALLA__."src/Http/");
define( "__TMP__", __MALLA__."src/System/Tmp/" );

$this->app->bind( "Core", function($app) {
	return new \Malla\Core\Support\Malla(
		new \Malla\Core\Support\Bootstrap($app)
	);
});


$this->app["core"] = Malla::load();

Malla::load( "finder", new \Malla\Core\Support\Finder );
Malla::load( "loader", new \Malla\Core\Support\Loader($this->app) );
Malla::load( "coredb", new \Malla\Core\Support\StorDB( $this->app["db"] ) );
Malla::load( "urls", new \Malla\Core\Support\Urls($this->app) );
Malla::load( "http", new \Malla\Core\Support\Http($this->app) );

Malla::load( "locale", new \Malla\Core\Support\Locale(
	$this->app["db"],
	$this->app["files"]
));

Malla::load( "model", new \Malla\Core\Model\Core);

/*
* Module */
Malla::load( "theme", new \Malla\Core\Support\Theme( $this->app["core"] ) );

/*
* HELPERS
* Helper Malla */
if( !function_exists("malla") ) {

	function malla( $key=null, $args=[], $merge=[] ) {
		return app("core")->load($key, $args, $merge);
	}
}

if( !function_exists("__path") ) {
	function __path( $path=null ) {
		return malla("urls")->path($path);
	}
}

if( !function_exists("__url") ) {

	function __url($path = null, $parameters = [], $secure = null) {
		return malla("urls")->url($path, $parameters, $secure);
	}
}

if( !function_exists("is_stable") ) {
	function is_stable($type, $slug) {
		return app("core")->isAppStart($type, $slug);
	}
}

if(!function_exists("__segment")) {
	function __segment($index=1, $match=null ) {
		if( !empty( ($segment = request()->segment($index)) ) ) {
			if(!empty($match)) return ($segment == $match);
			return $segment;
		}
	}
}

if(!function_exists("__segments")) {
	function __segments() {
		return request()->segments();
	}
}



if( is_stable("core", "core") ) {
	$this->map( $this->app["malla"] );
}

/*
* LOCALE */
if( function_exists("__islocale") ) {
	function __islocale($slug, $kernel) {
		return malla("locale")->isLocale($slug, $kernel);
	}
}

// if( !Malla::isAppStart("core", "malla") ) {

// 	Malla::load("loader")->run(\Malla\Package\Install\Kernel::class);
// }
