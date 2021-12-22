<?php
namespace Malla\Core;

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/

class Info {

  	public function app() {
  		return [
  			"type"			=> "core",
  			"slug"			=> "core",
  			"kernel"		   => \Malla\Core\Kernel::class,
  			"info"			=> \Malla\Core\Info::class,
  			"token"			=> NULL,
  			"activated" 	=> 0,
  		];
  	}

   public function depends() {
     return [
      \Malla\Guard\Info::class,
      \Malla\Alert\Info::class,
      \Malla\User\Info::class,
      \Malla\Menu\Info::class,
      \Malla\Dev\Info::class,
      \Malla\Cms\Info::class,
      \Malla\Widget\Info::class,
      \Malla\Skin\Info::class,

      \Malla\Info::class,
      \Malla\Admin\Info::class,

      \Malla\Theme\Rosy\Info::class,
     ];

   }

  	public function info() {
  		return [
  			"name"			=> "Core",
  			"author"		=> "Ing. Ramón A Linares Febles",
  			"email"			=> "rlinares4381@gmail.com",
  			"license"		=> "MIT",
  			"support"		=> "http://www.iipec.net",
  			"version"		=> "V-1.0",
  			"description" 	=> "Malla Core V-1.0",
  		];
  	}

  	public function meta() {
  		return [
  		];
  	}

  	public function handler($core) {
  		$core->create($this->app())->addInfo($this->info());
  	}
}
