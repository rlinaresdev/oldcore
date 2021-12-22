<?php
namespace Malla\Core\Model;

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/

use Malla\Core\Model\CoreInfo;
use Malla\Core\Model\CoreMeta;
use Malla\Core\Model\CoreConfig;
use Illuminate\Database\Eloquent\Model;

class Core extends Model {

  protected $table = "apps";

  protected $fillable = [
    "type",
		"slug",
		"kernel",
		"info",
		"token",
		"activated",
		"created_at",
		"updated_at"
  ];

/*
* Modificadores */
  public function setTokenAttribute($value) {
		if(is_null($value)) {
         $value = \Str::random( mt_rand(15, 25) );
      }
		return $this->attributes['token'] 	= $value;
	}

  /*
  * RELATIONS */
   public function credential() {
		return $this->hasOne(CoreInfo::class, "app_id");
	}

  public function configs() {
		return $this->hasOne(CoreConfig::class, "app_id");
	}

  public function meta() {
		return $this->hasMany(CoreMeta::class, "app_id");
  }
  public function locale() {
		return $this->hasMany(CoreLocale::class, "app_id");
  }

	/* PARAMTERS */
	public function config($key=NULL, $default=NULL) {

		if( ($data = $this->configs()->where("key", $key) )->count() > 0 ) {
			return $data->first()->value;
		}

		return $default;
	}

  public function skin($slug) {
		return $this->where("type", "theme")->where("slug", $slug)->first() ?? abort( 500, __("exception.500")."::".__("exception.exists", [
			"subject"	=> "La plantilla",
			"argument" 	=> $slug
		]) );
	}

  /*
  * METHODS */

   public function getLocales($slug) {
      if( ($data = $this->where("type", "language")->where("slug", $slug))->count() > 0 ) {
        return $data->first()->locale;
      }
   }

   public function saveLocale($data) {
      if( !empty($data) && is_array($data) ) {
         foreach ($data as $key => $value) {
            if( ($store = $this->locale()->where("key", $key))->count() > 0 ) {
               $store = $store->first();
               $store->update(["key" => $key, "value" => $value]);
            }
            else {
               $this->locale()->create(["key" => $key, "value" => $value]);
            }
         }
      }
   }

  /*
  * INFO METHODS */
  public function addInfo($data) {
    $this->credential()->create($data); return $this;
  }

  /*
  * ADD LOCALES METHODS */
  public function addTrans( $data ) {

    if(!empty($data) && is_array($data) ) {
      foreach($data as $key => $value) {
        $this->locale()->create([ "key" => $key, "value" => $value ]);
      }
    }

    return $this;
  }

  /*
  * CONFIG METHODS */
   public function addConfig($data) {
      if(empty($data) OR !is_array($data) ) return $this;
      foreach ($data as $key => $value) {
         $this->configs()->create(["key" => $key, "value" => $value]);
      }

     return $this;
   }

   /*
   * GET METHODS */
   public function scopeType( $query, $type=null ) {

      if( !empty($type) ) {
         return $query->where("type", $type);
      }

      return $query;
   }

   public function src( $type=null, $slug=null ) {
      if( ($data = $this->type($type)->where("slug", $slug))->count() > 0 ) {
         return $data->first();
      }
   }
}
