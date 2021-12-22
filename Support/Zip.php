<?php
namespace Malla\Core\Support;

/*
 *---------------------------------------------------------
 * ©IIPEC
 * Santo Domingo República Dominicana.
 *---------------------------------------------------------
*/

use ZipArchive;
use Illuminate\Support\Str;

class Zip {

   protected $app;

   protected $zip;

   protected $snapshots;

   public function __construct( $app, $zip ) {
      $this->app = $app;
      $this->zip = $zip;
   }

   public function load( ) {
      return $this;
   }

   public function stream( $upload ) {

      if( $this->zip->open(($this->realpath = $upload->getRealPath())) == true ) {

         for ($i=0; $i < $this->zip->numFiles; $i++) {

            $data = $this->zip->statIndex($i);
            $data = $data["name"];
            $data = trim($data, "/");
            $stors = explode('/', $data);

            if( end($stors) == "Info.php" ) {

               $fp = $this->zip->getStream($data);
               $string = null;
               if($fp) {
                  $i=0;
                  while (!feof($fp)) { $i++;
                     $string .= fread($fp, 2);
                  }
               }

               if( !empty($string) ) {
                  $this->snapshots = explode("\n", $string);
               }
            }
         }
      }

      return $this;
   }

   public function get($slug) {

      if( !empty($slug) && is_array($this->snapshots) ) {
         foreach ( $this->snapshots as $key => $str ) {

            $str = trim($str);

            if( Str::of($str)->is('"'.$slug.'"*')) {

               if( count(($data = explode('=>', $str ))) == 2 ) {
                  $str = end($data);
                  $str = str_replace('"', '', $str);
                  $str = str_replace(',', '', $str);
                  $str = trim($str);

                  return $str;
               }
            }
         }
      }
   }

   public function app($key=null) {
      $fillable = [
         "type",
         "slug",
         "kernel",
         "info",
         "token",
         "activated"
      ];

      if( !empty($key) && in_array($key, $fillable) ) {
         return $this->get($key);
      }

      if(empty($key)) {
         $data = null;
         foreach ( $fillable as $field ) {
            if( !empty( ($value = $this->get($field))) ) {
               $data[$field] = $value;
            }
         }

         return $data;
      }
   }

   public function info( $key=null ) {
      $fillable = [
         "name",
         "author",
         "email",
         "license",
         "support",
         "version",
         "description"
      ];

      if( !empty($key) && in_array($key, $fillable) ) {
         return $this->get($key);
      }

      if(empty($key)) {
         $data = null;
         foreach ( $fillable as $field ) {
            if( !empty( ($value = $this->get($field))) ) {
               $data[$field] = $value;
            }
         }

         return $data;
      }
   }
}
