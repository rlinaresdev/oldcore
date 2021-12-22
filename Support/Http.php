<?php
namespace Malla\Core\Support;

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/

class Http {

   protected $app;

   public function __construct( $app=null ) {
      $this->app = $app;
   }

   public function info() {
      return __CLASS__;
   }
}
