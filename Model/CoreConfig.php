<?php
namespace Malla\Core\Model;

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/

use Illuminate\Database\Eloquent\Model;

class CoreConfig extends Model {

  protected $table = "apps_config";

  protected $fillable = [
      "core_id",
      "key",
      "value",
      "activated"
  ];

  public $timestamps = false;

  //protected $dateFormat = 'U';
}
