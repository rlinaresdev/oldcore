<?php namespace Malla\Core\Support;

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/

use \Illuminate\Contracts\Routing\UrlGenerator;

class Urls {

	protected $base_dir = "malla";

    protected $secure = false;

	protected $taggs = [
		"urls" => [],
		"paths" => []
	];

	public function __construct( $urls ) {
	}

	public function baseDir() {
	  return config("base.dir", $this->base_dir);
	}

	public function replaceRul($tagg, $key, $value) {
		foreach( $this->taggs[$tagg] as $alia => $path ) {
			$value =  str_replace( $alia, $path, $value );
		}
		return $value;
	}

	public function addTag( $tagg=NULL, $segments=[] ) {
		foreach ($segments as $key => $value) {
			$this->taggs[$tagg][$key] = $this->replaceRul($tagg, $key, $value);
		}
	}

	function url( $path = null, $parameters = [], $secure = null)  {

		if(is_null($secure)) {
		   $secure = $this->secure;
		}

		if( is_null($path) ) {
			return app(UrlGenerator::class);
		}

		if(!empty(($urls = $this->taggs["urls"]))) {

			foreach ($urls as $key => $value) {
				$path = str_replace($key, $value, $path);
			}
		}

        return app(UrlGenerator::class)->to($path, $parameters, $secure);
    }

    public function publicUrl($path=null, $parameters = [], $secure = null) {
        return $this->url($this->baseDir()."/".trim($path, "/"), $parameters, $secure);
    }

    public function themeUrl($path=NULL, $parameters = [], $secure = null ) {
        return $this->publicUrl("themes/".trim($path, "/"), $parameters, $secure);
    }

    public function path($path=null) {

    	if(empty($path)) {
    		return app()->basePath($path);
    	}

    	if(!empty(($paths = $this->taggs["paths"]))) {
        	foreach( $paths as $key => $value ) {
				$path = str_replace($key, $value, $path);
			}
      }

        return $path;
    }

    public function publicPath($path=null) {
        return public_path($this->baseDir()."/".trim($path, "/"));
    }

    public function themePath($skin=null) {
        return $this->publicPath("themes/".$skin );
    }
}
