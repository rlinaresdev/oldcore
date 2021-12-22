<?php
namespace Malla\Core\Console;

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/

class Handler {
  public function getCommands() {
    return [
      \Malla\Core\Console\Commands\SetCommand::class,
    ];
  }
}
