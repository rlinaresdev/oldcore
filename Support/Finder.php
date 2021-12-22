<?php namespace Malla\Core\Support;

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/

class Finder {

	public function toArray(){
		return (array) $this;
	}

	public function map( $source='./', $depth=1, $hidden=false ) {
		
		if( $fp = @opendir($source) ) {

			$data		= array();
			$new_depth	= $depth - 1;
			$source 	= rtrim($source, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

			while (false !== ($file = readdir($fp))) 
			{
				if ( !trim($file, '.') OR ($hidden == false && $file[0] == '.')) {
					continue;
				}

				if (($depth < 1 OR $new_depth > 0) && @is_dir($source.$file)) {
					$data[$file] = $this->map($source.$file.DIRECTORY_SEPARATOR, $new_depth, $hidden);
				}
				else {
					$data[] = $file;					
				}
			}

			closedir($fp);

			return $data;
		}

		return false;
	}
}
