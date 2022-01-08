<?php
namespace Malla\Core\Model;

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/

use Illuminate\Database\Eloquent\Model;

class CoreRoute extends Model {

   protected $table = "routes";

   protected $fillable = [
      "domain",
      "method",
      "prefix",
      "uri",
      "name",
      "action",
      "middleware"
   ];

}
