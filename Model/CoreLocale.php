<?php
namespace Malla\Core\Model;

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/

use Illuminate\Database\Eloquent\Model;

class CoreLocale extends Model {

  protected $table = "apps_locale";

  protected $fillable = [
      "app_id",
		"key",
		"value",
		"activated"
  ];

  public $timestamps = false;

  //protected $dateFormat = 'U';
}
