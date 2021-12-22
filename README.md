## CORE
Core V-1.0 | Sistema Central para Malla

### URLS

Urls etiquetada

```php
	
	Malla::load("urls")->addTag("urls", [
		"__path"	=> request()->path(),
		"__theme"	=> "admin/theme/lists"
	]);

```

Utilizando la ruta etiquetada

```html

	<a href="{{__url("__path")}}"> Ruta Actual </a>

	<a href="{{__url("__theme")}}"> Plantillas </a>
```

### HELPER

Instaciar malla desde un proveedor de servicio.

```php
function register() {
	$malla = $this->app["malla"];
}

```
Desde el helper malla

```php

$malla = malla();

```


Llamando una libraría en especifico.

```php
$map = malla("finder")->map("../", 1);

```

### Cargar una libraría

```php

malla("alianame", new \Vendor\Library\ClassName());

malla("alianame", \Vendor\Library\ClassName::class);

```


### Montar el Kernel de una Libraría

```php

malla("loader")->run(\Vendor\Library\Kernel::class);

```