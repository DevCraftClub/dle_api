<?php

declare(strict_types=1);

/**
 * Автозагрузка DleApi + DevCraft\Dle (модели Admin) для swagger-php.
 */
require dirname(__DIR__) . '/vendor/autoload.php';

$sdk = getenv('DLE_ADMIN_SDK');
if($sdk === false || $sdk === '') {
	$sdk = dirname(__DIR__, 2) . '/devcraft/src/sdk/dle';
}

$sdk = rtrim($sdk, '/');
if(!is_dir($sdk . '/Schema')) {
	fwrite(STDERR, "OpenAPI: нет моделей Schema в {$sdk}\n");
	exit(1);
}

spl_autoload_register(static function(string $class) use ($sdk): void {
	$prefix = 'DevCraft\\Dle\\';
	if(!str_starts_with($class, $prefix)) {
		return;
	}

	$rel  = str_replace('\\', '/', substr($class, strlen($prefix)));
	$file = $sdk . '/' . $rel . '.php';
	if(is_file($file)) {
		require $file;
	}
});
