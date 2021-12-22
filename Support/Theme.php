<?php
namespace Malla\Core\Support;

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/

use Malla\Skin\Facade\Skin;

class Theme {

	protected $db;

	public function __construct( $app ) {
		$this->db = $app->load("coredb")->store;
	}

	public function all() {
    if( ($data = $this->db->where("type", "theme")->where("activated", 1))->count() > 0 ) {
      return $data->get();
    }
	}

	public function syncUP( $app, $HTTP, $LANG ) {

		if( !empty( ($themes = $this->all()) ) ) {
			foreach ($themes as $theme) {
				$info = $theme->info;
	      $info = (new $info);

				if( method_exists($info, "sync") ) {
	        if( $app['files']->exists($info->sync()) && !is_array($info->sync()) ) {
	          require_once($info->sync());
	        }
	      }
			}
		}
	}
}
