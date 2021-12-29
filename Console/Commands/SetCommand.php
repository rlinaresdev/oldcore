<?php
namespace Malla\Core\Console\Commands;

/*
  *---------------------------------------------------------
  * ©IIPEC
  * Santo Domingo República Dominicana.
  *---------------------------------------------------------
*/

use Malla\Core\Info;
use Malla\Core\Model\Core;
use Illuminate\Console\Command;
use Malla\Core\Database\Migration\TableSchema;

class SetCommand extends Command {

  protected $core;

  protected $malla;

  protected $signature     = 'core {actions : init|reset|reload|update|start|stop}';
  protected $description   = 'Core Aplication Command';

  public function __construct( Info $malla, TableSchema $schema, Core $core ) {

    parent::__construct();

    $this->core     = $core;
    $this->malla    = $malla;
    $this->schema   = $schema;
  }

  public function handle() {

    $app      = $this->malla->app();
    $package  = $this->malla->info();

    $this->info("© ".$package["description"]);
    $this->info(str_repeat('--', 30));

    $method = $this->argument("accion");

    if( method_exists($this, $method) ) {
			return $this->{$method}();
		}
    else {
      $this->error("Accion {$method} no disponible");
      $this->error("Disponible: init|reset|reload|update");
    }
  }

  public function init() {
    $this->info(" Inicializando");

    if( is_array(($data = $this->schema->up())) ) {
      foreach ($data as $line) {
        $this->info( $line );
      }
    }

    $this->info(" -- Registrando componente");
    $this->malla->handler($this->core);

    $this->info(" -- Registrando Depedencias");
    $this->depends("install");

    $this->info(" -- End");
  }

  public function reset() {
    $this->info(" Reset schema");
    $this->depends("uninstall");

    if( is_array(($data = $this->schema->down())) ) {
      foreach ($data as $line) {
        $this->info( $line );
      }
    }
  }

   public function reload() {
      $this->info("Reload...");
      $this->reset();
      $this->init();
   }

   public function start() {
      if( !empty(($store = $this->core->src("core", "core"))) ) {
         $store->activated = 1;
         $store->save();

         $this->info("Core activado");
      }
   }

   public function stop() {
      if( !empty(($store = $this->core->src("core", "core"))) ) {
         $store->activated = 0;
         $store->save();

         $this->info("Core desactivado");
      }
   }

  public function update() { $this->info("Update Method"); }

   public function depends($method="install") {

      if( method_exists($this->malla, "depends") ) {
         if( !empty( ($depends = $this->malla->depends()) ) ) {

           foreach ( $this->malla->depends() as $app ) {
             if(method_exists(($app = new $app), $method)) {
               if($method == "install") {
                 $app->{$method}($this->core);
               }
               if($method == "uninstall") {
                 $app->{$method}($this->core);
               }
             }
             if( $method != "uninstall" ) {
               $app->handler($this->core);
             }
           }
         }
      }
   }

}
