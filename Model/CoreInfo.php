<?php
namespace Malla\Core\Model;

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/

use Illuminate\Database\Eloquent\Model;

class CoreInfo extends Model {

  protected $table = "apps_info";

  protected $fillable = [
    "app_id",
		"name",
		"author",
		"email",
		"avatar",
		"license",
		"support",
		"version",
		"description",
		"comment"
  ];
}
