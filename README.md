## CORE
Core V-1.0 | Sistema Central para Malla

### URLS

Urls etiquetada

```php

	Core::load("urls")->addTagUrl([
		"__path"	=> request()->path(),
		"__theme"	=> "admin/theme/lists"
	]);

```

### URLS

```php

   Core::addTagPath([
      "__neblina"     => public_path()."/__malladir/admin/theme/neblina",
   ]);

```

Utilizando la ruta etiquetada

```html

	<a href="{{__url("__path")}}"> Ruta Actual </a>

	<a href="{{__url("__theme")}}"> Plantillas </a>
```

Instaciar malla desde un proveedor de servicio.

```php

   function register() {
   	$core = $this->app["core"];
   }

```

Desde el helper malla

```php

   $malla = malla();

```


Llamando una libraría en especifico.

```php

   $LISTS      = Core::load("finder")->map("../", 1); // Finder Library

   $URLS       = Core::load("urls"); // Urls Library

   $COREDB     = Core::load("coredb"); // Core DB Library

   $COREMODEL  = Core::load("model"); // Core Model Library

```

### Cargar una libraría

```php

   Core::load( "alianame", new \Vendor\Library\ClassName() );

```


### Montar el Kernel de una Libraría

```php

   Core::run( \Vendor\Library\Kernel::class );

```

## HELPERS

Listar los segmentos de una url en un arreglo

-- Los segmentos de una url el un arreglo lineal en una urls
-- http://domain.lc/segment1/segment21

```php

   $segments = __segments();

   if( in_array( "portada", $segments ) ) {
      // Si portada se encuentra en el arreglo la salida es true
   }

```

Evaluar los segmento de la url

```php

   if( __segment(1, "portada") ) {
      // Si el segmento 1 es igual a portada la salida es true      
   }

```
