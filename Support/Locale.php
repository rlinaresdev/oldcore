<?php namespace Malla\Core\Support;

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/

class Locale {

	private static $db;

	private static $file;

	public function __construct( $db, $file ) {
		self::$db 	= $db;
		self::$file = $file;
	}

	public function isCurrent($slug, $kernel) {
		return ( ($slug == $this->slug) && ($kernel == $this->lang) );
	}

	public function getLocale() {
		$app = self::$db->table("apps")->where("type", "language");

		$app->where("slug", $this->slug);
		$app->where("kernel", $this->lang);
		$app->select("id", "type", "slug", "kernel", "info");

		if( $app->count() > 0 ) {
			return $app->first();
		}
	}

	public function getFileMetaName( $meta ) {
		return "__".str_replace('_', null, $meta->kernel);
	}

	public function getPathMeta( $meta ) {
		return __MALLA__."src/Http/Meta/Translations/".$this->getFileMetaName($meta).".php";
	}

	public function getFileMeta( $lang ) {
		if( self::$file->exists($this->getPathMeta($lang)) ) {
			$meta = "\\Malla\\Http\\Meta\\Translations\\".$this->getFileMetaName($lang);

			return new $meta;
		}
	}

	public function configLocale() {

		$app = self::$db->table("apps");

		$app->where("type", "language");
		$app->where("activated", 1);
		$app->select("id", "type", "slug", "kernel", "info");

		if( $app->count() > 0 ) {
			$app = $app->first();

			$this->lang 			= $app->kernel;
			$this->slug 			= $app->slug;
			$this->description 	= $app->info;

			$metaPath = $this->getPathMeta($app);

			if( self::$file->exists($metaPath) ) {
				$meta = "\\Malla\\Http\\Meta\\Translations\\".$this->getFileMetaName($app);

				$this->meta = new $meta;
			}
			else {
				$this->language = $app;
			}
		}
	}

	public function has($key="slug") {
		return array_key_exists( $key, (array) $this );
	}

	public function hasMetaData($lang=null) {
		if(!empty($lang)) {
			return self::$file->exists($this->getPathMeta($lang));
		}

		return false;
	}

	public function loadMetaData() {
		if(!empty( ($grammars = $this->meta->translations()) ) && is_array($grammars) ) {
			app("translator")->addLines($grammars, $this->slug);
		}
	}

	public function loadLanguageDB($language) {
		$app = self::$db->table("apps_locale");
		$app->where("app_id", $language->id);

		if( $app->count() > 0 ) {
			$grammars = NULL;

			foreach ($app->get() as $trans) {
				$grammars[$trans->key] = $trans->value;
			}

			if( !empty($grammars) && is_array($grammars) ) {
				app("translator")->addLines($grammars, $language->slug);
			}
		}
	}

	public function loadGrammars( $load="mixed" ) {
		$this->configLocale();
		
		if( $this->has()) {

			app()->setLocale($this->slug);

			if( $this->has("meta") ) {
				$this->loadMetaData();
			}
			elseif($this->has("language")) {
				$this->loadLanguageDB($this->language);
			}
		}
		return NULL;
	}

	public function checkMetaFile($meta) {
      $rols["name"]        = "required";
      $rols["slug"]        = "required";
      $rols["description"] = "required";

      return (validator( $meta->header(), $rols)->errors()->any() == false);
   }

	public function deployMeta($lang) {
		if( is_object( ($meta = $this->getFileMeta($lang)) ) ) {

         if( $this->checkMetaFile( $meta ) ) {

            $header        = $meta->header();
            $translations  = $meta->translations();

            $lang->slug = $header["slug"];
            $lang->kernel = $header["name"];
            $lang->info = $header["description"];

            if( $lang->save() ) {
               $lang->saveLocale($translations);

					return true;
            }
         }
      }

		return false;
	}

	public function tab($index=0) {
		return str_repeat(" ", $index);
	}

	public function writeHeader($lang, $index=4) {
		$tag = $this->tab();
		$tag .= "return [\n";

		$tag .= $this->tab($index+4);
		$tag .= '"slug" => "'.$lang->slug.'",'."\n";
		$tag .= $this->tab($index+4);
		$tag .= '"name" => "'.$lang->kernel.'",'."\n";
		$tag .= $this->tab($index+4);
		$tag .= '"description" => "'.$lang->info.'"'."\n";

		$tag .= $this->tab($index+2);
		$tag .= "];\n";

		return $tag;
	}

	public function writeTranslation( $lang, $index=4 ) {
		$tag = $this->tab();
		$tag .= "return [\n";
		if( !empty($lang->locale) ) {

			$beforeValue = null;

			$language = $lang->locale();
			$language->orderBy("key", "ASC");

			foreach ($language->get() as $key => $trans ) {


				if( $key > 0 ) {
					$char = $trans->key[0];
					$char2 = $beforeValue[0];

					if( $char != $char2 ) {
						$tag .= "\n";
					}
				}

				$tag .= $this->tab($index+4);
				$tag .= '"'.$trans->key.'" => "'.$trans->value.'",'."\n";

				$beforeValue = $trans->key;
			}
		}
		$tag .= $this->tab($index+2);
		$tag .= "];\n";

		return $tag;
	}

	public function putMeta($lang) {

		$meta = self::$file->get(__DIR__."/Stubs/language");
		$meta = str_replace(":header", $this->writeHeader($lang), $meta);
		$meta = str_replace(":translations", $this->writeTranslation($lang), $meta);
		$meta = str_replace(":name", $this->getFileMetaName($lang), $meta);

		self::$file->put($this->getPathMeta($lang), $meta);
	}

	public function refreshMeta($lang) {
		if(self::$file->exists( ($path = $this->getPathMeta($lang)) ) ) {
			self::$file->delete($path);
		}

		$this->putMeta($lang);
	}

	public function toggleLang( $value=0 ) {

		$db = self::$db->table("apps");
		$db->where("type", "language");
		$db->where("slug", $this->slug);
		$db->where("kernel", $this->lang);

		if($db->count()) {
			return $db->update(["activated" => $value]);
		}

		return false;
	}
}
