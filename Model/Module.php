<?php
namespace Malla\Core\Model;

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/

use Malla\Core\Model\Core;

class Module extends Core {
   public function name() {
      return $this->credential->name;
   }
   public function token() {
      return $this->token;
   }
   public function author() {
      return $this->credential->author;
   }
   public function email() {
      return $this->credential->email;
   }
   public function license() {
      return $this->credential->license;
   }
   public function support() {
      return $this->credential->support;
   }
   public function version() {
      return $this->credential->version;
   }
   public function description() {
      return $this->credential->description;
   }
}
