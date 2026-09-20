<?php

declare(strict_types=1);

/**
 * Генерация apidata/openapi.yaml: модели Schema из текущего zip Admin на витрине.
 */
$apiRoot = dirname(__DIR__);
$repoRoot = dirname($apiRoot, 2);
$outFile = $repoRoot . '/apidata/openapi.yaml';
$bin = $apiRoot . '/vendor/zircote/swagger-php/bin/openapi';
$bootstrap = __DIR__ . '/openapi-bootstrap.php';
$fetch = $repoRoot . '/.github/scripts/fetch-admin-sdk.sh';

if(!is_file($bin)) {
	fwrite(STDERR, "OpenAPI: нет {$bin} — сначала composer install\n");
	exit(1);
}

$sdk = getenv('DLE_ADMIN_SDK');
if($sdk === false || $sdk === '') {
	$sdk = $apiRoot . '/var/dle-admin-sdk';
	putenv('DLE_ADMIN_SDK=' . $sdk);
	$_ENV['DLE_ADMIN_SDK'] = $sdk;
}

$skipFetch = getenv('OPENAPI_SKIP_FETCH') === '1' && is_dir(rtrim($sdk, '/') . '/Schema');
if(!$skipFetch) {
	$cmd = 'DLE_ADMIN_SDK=' . escapeshellarg($sdk) . ' ' . escapeshellarg($fetch);
	passthru($cmd, $fetchCode);
	if($fetchCode !== 0) {
		exit($fetchCode);
	}
}

$sdk = rtrim((string) getenv('DLE_ADMIN_SDK'), '/');
$schema = $sdk . '/Schema';
$xfield = $sdk . '/Xfield/Schema';
if(!is_dir($schema) || !is_dir($xfield)) {
	fwrite(STDERR, "OpenAPI: нет Schema в {$sdk}\n");
	exit(1);
}

$cmd = [
	PHP_BINARY,
	$bin,
	'-b',
	$bootstrap,
	'-o',
	$outFile,
	'-f',
	'yaml',
	$apiRoot . '/src/OpenApi',
	$schema,
	$xfield,
];

$line = implode(' ', array_map('escapeshellarg', $cmd));
passthru($line, $code);
if($code !== 0) {
	exit($code);
}
if(!is_file($outFile) || filesize($outFile) < 32) {
	fwrite(STDERR, "OpenAPI: файл не записан: {$outFile}\n");
	exit(1);
}
echo "OpenAPI: записан {$outFile} (" . filesize($outFile) . " байт)\n";
