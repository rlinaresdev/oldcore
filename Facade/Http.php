<?php
namespace Malla\Core\Facade;

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/
use Illuminate\Support\Facades\Facade;

class Http extends Facade {
   public static function getFacadeAccessor(){return "Http";}
}
