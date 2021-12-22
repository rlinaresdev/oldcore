<?php namespace Malla\Core\Support;

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/

use Illuminate\Support\Facades\Schema;

class StorDB {

	protected $db;

	protected $table = "apps";

	public $store;

	public function __construct( $db ) {
		$this->db = $db;
		$this->store = $db->table($this->table);
	}

	public function has($type=NULL, $slug=NULL)	{

		if(empty($type) OR empty($slug)) return FALSE;

		if(Schema::hasTable($this->table)) {
			return ( $this->db->table($this->table)
												->where("type", $type)->where("slug", $slug)->count() > 0 );
		}

		return FALSE;
	}

	public function hasType( $type=NULL ) {
		return  ($this->store()->where( "type", $type )->count() > 0);
	}

	public function isInstalled($type=NULL, $slug=NULL) {
		if($this->has($type, $slug)) {
			if( !empty( ($query = $this->get($type, $slug)) ) ) {
				return $query->activated;
			}
		}

		return FALSE;
	}

	public function count($table=NULL)	{
		return $this->db->table($table)->count();
	}

	public function get($type=NULL, $slug=NULL) {

		$data = $this->db->table($this->table);
				  $data->where("type", $type);
				  $data->where("slug", $slug);

		return $data->first() ?? null;
	}

	public function getType($type=NULL)	{
		$data = $this->db->table($this->table);
				  $data->where("type", $type);

		return $data->get() ?? null;
	}

	public function getParam($type=NULL, $ID=NULL) {
		$data = $this->db->table($this->table."_params");
					$data->where($this->table."_id", $ID);
					$data->where("type", $type);

		return $data->get(["type", "key", "value"]) ?? null;
	}

	public function getConfig($type, $app) {
		$data = $this->db->table($this->table."_config");
					$data->where("app_id", $app->id);

		return $data->get(["key", "value"]) ?? null;
	}

	public function getLocale($ID=NULL) {
		$data = $this->db->table($this->table."_params");
					$data->where("type", "lang");
					$data->where($this->table."_id", $ID);

		return $data->get(["type", "key", "value"]) ?? null;
	}

	public function queryThemes($activated=0) {
		$data = $this->db->table($this->table);
					$data->where("type", "theme");
					$data->where("activated", $activated);

		return $data->get(["type", "slug", "info", "token", "activated"]);
	}
}
