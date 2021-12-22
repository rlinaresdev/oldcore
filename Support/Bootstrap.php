<?php 
namespace Malla\Core\Support;

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/



class Bootstrap {

	protected static $app;

	protected static $APP = [];

	public function __construct( $app ) {	
		self::$app = $app;
	}

	public function load($malla, $key=NULL, $args=NULL, $params=NULL) {

		if(empty($key)) return $malla;

		if(!empty($key) && empty($args)) {

			if( array_key_exists( $key, self::$APP ) ) {
				return self::$APP[$key];
			}
		}

		if(!empty($key) && empty($args) && array_key_exists($key, self::$app->getBindings())) {
			return self::$app[$key];
		}

		if( !empty($key) && !empty($args) && (is_string($args) OR is_object($args) ) ) {

			if( !array_key_exists($key, self::$APP) && is_string($args) ) {
				self::$APP[$key] = new $args($params);
			}

			if( !array_key_exists($key, self::$APP) && is_object($args) ) {
				self::$APP[$key] = $args;
			}
		}

		if( !empty($key) && !empty($args) &&  $args instanceof  \Closure ) {

			if( !isset(self::$APP[$key]) ) {  
				self::$APP[$key] = $args($params);
			}
		}
	}
}